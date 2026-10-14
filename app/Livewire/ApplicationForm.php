<?php

namespace App\Livewire;

use App\Apartments\Apartment;
use App\Apartments\ApartmentRepository;
use Livewire\Attributes\Url;

/**
 * Application for one or more apartments (module:apply): the contact form with the
 * apartment(s) on top of the message, so the marketer's mail and contact-form itself
 * stay unchanged. ?wohnung=REF preselects one; with apply.multiple the visitor adds
 * more from the current list (buyers and tenants often want two or three).
 */
class ApplicationForm extends ContactForm
{
    #[Url(as: 'wohnung')]
    public string $apartment = '';

    /** @var array<int, string> refs, multiple mode only */
    public array $selected = [];

    /** Bound to the "add apartment" select; moved into $selected right away. */
    public string $add = '';

    /** @var array<string, string>|null per request; the list is read once */
    protected ?array $options = null;

    public function mount(): void
    {
        if ($this->multiple() && array_key_exists($this->apartment, $this->options())) {
            $this->selected = [$this->apartment];
        }
    }

    public function updatedAdd(string $ref): void
    {
        if (array_key_exists($ref, $this->options()) && ! in_array($ref, $this->selected, true)) {
            $this->selected[] = $ref;
        }

        $this->add = '';
    }

    public function remove(string $ref): void
    {
        $this->selected = array_values(array_diff($this->selected, [$ref]));
    }

    protected function fieldConfig(): array
    {
        return config('apply.fields', parent::fieldConfig());
    }

    /** @return array<string,mixed> */
    protected function rules(): array
    {
        $rules = parent::rules();

        if ($this->multiple() && $this->options()) {
            $rules['selected'] = ['array', 'min:1'];
            $rules['selected.*'] = ['string', 'in:'.implode(',', array_keys($this->options()))];
        }

        return $rules;
    }

    /** @return array<string,string> */
    protected function messages(): array
    {
        return parent::messages() + [
            'selected.min' => 'Bitte wählen Sie mindestens eine Wohnung aus.',
            'selected.*.in' => 'Diese Wohnung ist nicht mehr verfügbar.',
        ];
    }

    protected function contactData(): array
    {
        $data = parent::contactData();
        $refs = $this->refs();

        if ($refs) {
            $subject = count($refs) > 1 ? 'Bewerbung für Wohnungen ' : 'Bewerbung für Wohnung ';
            $data['message'] = trim($subject.implode(', ', $refs)."\n\n".($data['message'] ?? ''));
        }

        return $data;
    }

    /** @return array<int, string> */
    public function refs(): array
    {
        if ($this->multiple()) {
            return $this->selected;
        }

        return $this->apartment !== '' ? [$this->apartment] : [];
    }

    public function multiple(): bool
    {
        return (bool) config('apply.multiple');
    }

    /**
     * Apartments that can be applied for, ref => label ("A.01 – 2.5 Zimmer, EG").
     *
     * @return array<string, string>
     */
    public function options(): array
    {
        $repository = app(ApartmentRepository::class);
        $statuses = config('apply.statuses', ['free', 'reserved']);

        return $this->options ??= $repository->all()
            ->filter(fn (Apartment $a) => in_array($a->status->value, $statuses, true))
            ->mapWithKeys(fn (Apartment $a) => [$a->ref => $a->ref.collect([
                $a->rooms ? $a->roomsLabel().' Zimmer' : null,
                $repository->floorLabel($a->floor),
            ])->filter()->whenNotEmpty(fn ($parts) => collect([' – '.$parts->implode(', ')]))->implode('')])
            ->all();
    }

    public function render()
    {
        return view('livewire.application-form');
    }
}
