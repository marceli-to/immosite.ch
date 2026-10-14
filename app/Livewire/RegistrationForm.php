<?php

namespace App\Livewire;

use App\Livewire\Concerns\ContactFields;
use App\Mail\RegistrationConfirmation;
use App\Mail\RegistrationReceived;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

/**
 * Registration form of the landing page: stores the registration, sends the
 * sender a confirmation and, if configured, the marketer a notification. Which fields exist is defined in config/registration.php.
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
        // Bot: fake success, store nothing.
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

        // The registration is stored; if a mail fails, the sender shouldn't see an
        // error and try again. Each mail on its own, so one failure doesn't skip the other.
        if ($notify = array_filter(array_map('trim', explode(',', (string) config('registration.notify'))))) {
            try {
                Mail::to($notify)->send(new RegistrationReceived($registration));
            } catch (\Throwable $e) {
                report($e);
            }
        }

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
