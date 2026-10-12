<?php

namespace App\Livewire;

use Livewire\Attributes\Url;

/**
 * Application for one apartment (module:apply): the contact form with the
 * apartment preselected. The apartment goes on top of the message, so the
 * marketer's mail and contact-form itself stay unchanged.
 */
class ApplicationForm extends ContactForm
{
    #[Url(as: 'wohnung')]
    public string $apartment = '';

    protected function fieldConfig(): array
    {
        return config('apply.fields', parent::fieldConfig());
    }

    protected function contactData(): array
    {
        $data = parent::contactData();

        if ($this->apartment !== '') {
            $data['message'] = trim('Bewerbung für Wohnung '.$this->apartment."\n\n".($data['message'] ?? ''));
        }

        return $data;
    }

    public function render()
    {
        return view('livewire.application-form');
    }
}
