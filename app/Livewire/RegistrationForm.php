<?php

namespace App\Livewire;

use App\Mail\RegistrationConfirmation;
use App\Models\Registration;
use App\Support\Turnstile;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

/**
 * Anmeldeformular der Landingpage: speichert die Anmeldung und schickt dem
 * Absender eine Bestaetigung. Welche Felder es gibt, steht in config/registration.php.
 */
class RegistrationForm extends Component
{
    /** @var array<int,string> */
    public array $interests = [];

    public string $first_name = '';

    public string $last_name = '';

    public string $street = '';

    public string $zip_city = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    public bool $privacy = false;

    /** Honeypot, siehe x-form.honeypot. */
    public string $website = '';

    public string $turnstileToken = '';

    public bool $submitted = false;

    /** Konfigurierte Felder: required / optional / false. */
    public function field(string $name): string|false
    {
        return config("registration.fields.{$name}", false);
    }

    /** @return array<string,mixed> */
    protected function rules(): array
    {
        $options = array_keys(config('registration.interests', []));

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'privacy' => ['accepted'],
        ];

        if ($options) {
            $rules['interests'] = ['required', 'array', 'min:1'];
            $rules['interests.*'] = ['string', 'in:'.implode(',', $options)];
        }

        foreach (['street' => 255, 'zip_city' => 255, 'phone' => 255, 'message' => 5000] as $name => $max) {
            if ($mode = $this->field($name)) {
                $rules[$name] = [$mode === 'required' ? 'required' : 'nullable', 'string', "max:{$max}"];
            }
        }

        return $rules;
    }

    /** @return array<string,string> */
    protected function messages(): array
    {
        return [
            'interests.required' => 'Bitte wählen Sie mindestens eine Option aus.',
            'interests.min' => 'Bitte wählen Sie mindestens eine Option aus.',
            'first_name.required' => 'Bitte geben Sie Ihren Vornamen ein.',
            'last_name.required' => 'Bitte geben Sie Ihren Namen ein.',
            'street.required' => 'Bitte geben Sie Strasse und Nr. ein.',
            'zip_city.required' => 'Bitte geben Sie PLZ und Ort ein.',
            'email.required' => 'Bitte geben Sie Ihre E-Mail-Adresse ein.',
            'email.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
            'phone.required' => 'Bitte geben Sie Ihre Telefonnummer ein.',
            'message.required' => 'Bitte geben Sie eine Nachricht ein.',
            'privacy.accepted' => 'Bitte akzeptieren Sie die Datenschutzerklärung.',
            'max' => 'Die Eingabe ist zu lang.',
        ];
    }

    /** Prueft ein Feld, sobald es verlassen wird, nicht erst beim Absenden. */
    public function updated(string $property): void
    {
        if (array_key_exists(explode('.', $property)[0], $this->rules())) {
            $this->validateOnly($property);
        }
    }

    public function submit(): void
    {
        // Bot hat das unsichtbare Feld ausgefuellt: Erfolg vortaeuschen, nichts speichern.
        if (filled($this->website)) {
            $this->submitted = true;

            return;
        }

        $this->validate();

        if (! Turnstile::verify($this->turnstileToken)) {
            $this->addError('turnstileToken', 'Die Spam-Prüfung ist fehlgeschlagen. Bitte versuchen Sie es erneut.');
            $this->dispatch('turnstile:reset');

            return;
        }

        $registration = Registration::create([
            'interests' => array_values($this->interests) ?: null,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'street' => $this->street ?: null,
            'zip_city' => $this->zip_city ?: null,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'message' => $this->message ?: null,
        ]);

        // Die Anmeldung ist gespeichert; scheitert nur die Bestaetigung, soll der
        // Absender keinen Fehler sehen und es nicht noch einmal versuchen.
        try {
            Mail::to($registration->email)->send(new RegistrationConfirmation);
        } catch (\Throwable $e) {
            report($e);
        }

        $this->reset(['interests', 'first_name', 'last_name', 'street', 'zip_city', 'email', 'phone', 'message', 'privacy', 'turnstileToken']);

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
