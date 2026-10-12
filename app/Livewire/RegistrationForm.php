<?php

namespace App\Livewire;

use App\Livewire\Concerns\ContactFields;
use App\Mail\RegistrationConfirmation;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

/**
 * Anmeldeformular der Landingpage: speichert die Anmeldung und schickt dem
 * Absender eine Bestaetigung. Welche Felder es gibt, steht in config/registration.php.
 */
class RegistrationForm extends Component
{
    use ContactFields;

    /** @var array<int,string> */
    public array $interests = [];

    public bool $submitted = false;

    protected function fieldConfig(): array
    {
        return config('registration.fields', []);
    }

    /** @return array<string,mixed> */
    protected function rules(): array
    {
        $rules = $this->contactRules();

        if ($options = array_keys(config('registration.interests', []))) {
            $rules['interests'] = ['required', 'array', 'min:1'];
            $rules['interests.*'] = ['string', 'in:'.implode(',', $options)];
        }

        return $rules;
    }

    public function submit(): void
    {
        // Bot: Erfolg vortaeuschen, nichts speichern.
        if ($this->isBot()) {
            $this->submitted = true;

            return;
        }

        $this->validate();

        if (! $this->passesTurnstile()) {
            return;
        }

        $registration = Registration::create([
            'interests' => array_values($this->interests) ?: null,
            ...$this->contactData(),
        ]);

        // Die Anmeldung ist gespeichert; scheitert nur die Bestaetigung, soll der
        // Absender keinen Fehler sehen und es nicht noch einmal versuchen.
        try {
            Mail::to($registration->email)->send(new RegistrationConfirmation);
        } catch (\Throwable $e) {
            report($e);
        }

        $this->resetContactFields();
        $this->reset('interests');

        if ($route = config('registration.redirect_route')) {
            $this->redirectRoute($route);

            return;
        }

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.registration-form', [
            'options' => config('registration.interests', []),
        ]);
    }
}
