<?php

namespace App\Livewire\Concerns;

use App\Support\Turnstile;

/**
 * Gemeinsame Felder fuer Anmelde- und Kontaktformular: Name, Adresse, E-Mail,
 * Telefon, Nachricht, Datenschutz, Honeypot, Turnstile. Welche der optionalen
 * Felder es gibt, liefert das Formular ueber fieldConfig().
 */
trait ContactFields
{
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

    /** @return array<string, string|false> street, zip_city, phone, message => required|optional|false */
    abstract protected function fieldConfig(): array;

    /** @return array<string, string|false> */
    public function fields(): array
    {
        return array_merge(['street' => false, 'zip_city' => false, 'phone' => false, 'message' => false], $this->fieldConfig());
    }

    /** @return array<string,mixed> */
    protected function contactRules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'privacy' => ['accepted'],
        ];

        $max = ['street' => 255, 'zip_city' => 255, 'phone' => 255, 'message' => 5000];

        foreach ($this->fields() as $name => $mode) {
            if ($mode) {
                $rules[$name] = [$mode === 'required' ? 'required' : 'nullable', 'string', "max:{$max[$name]}"];
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

    /** Bot hat das unsichtbare Feld ausgefuellt. */
    protected function isBot(): bool
    {
        return filled($this->website);
    }

    /** Turnstile pruefen; bei Fehler Meldung setzen und Widget zuruecksetzen. */
    protected function passesTurnstile(): bool
    {
        if (Turnstile::verify($this->turnstileToken)) {
            return true;
        }

        $this->addError('turnstileToken', 'Die Spam-Prüfung ist fehlgeschlagen. Bitte versuchen Sie es erneut.');
        $this->dispatch('turnstile:reset');

        return false;
    }

    /** @return array<string, string|null> Eingaben der angezeigten Felder, leere als null. */
    protected function contactData(): array
    {
        $data = ['first_name' => $this->first_name, 'last_name' => $this->last_name, 'email' => $this->email];

        foreach ($this->fields() as $name => $mode) {
            $data[$name] = $mode ? ($this->{$name} ?: null) : null;
        }

        return $data;
    }

    protected function resetContactFields(): void
    {
        $this->reset(['first_name', 'last_name', 'street', 'zip_city', 'email', 'phone', 'message', 'privacy', 'turnstileToken']);
    }
}
