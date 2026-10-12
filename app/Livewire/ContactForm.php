<?php

namespace App\Livewire;

use App\Livewire\Concerns\ContactFields;
use App\Mail\ContactConfirmation;
use App\Mail\ContactInquiry;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

/**
 * Contact form: mails the inquiry to the marketer and a confirmation
 * to the sender, then redirects to /danke (own URL, trackable as a conversion,
 * a reload doesn't submit again).
 */
class ContactForm extends Component
{
    use ContactFields;

    protected function fieldConfig(): array
    {
        return config('contact.fields', []);
    }

    /** @return array<string,mixed> */
    protected function rules(): array
    {
        return $this->contactRules();
    }

    /** @return array<int,string> */
    public static function recipients(): array
    {
        $list = config('contact.recipients') ?: config('site.marketer.email');

        return array_values(array_filter(array_map('trim', explode(',', (string) $list))));
    }

    public function submit(): void
    {
        if ($this->isBot()) {
            $this->redirectRoute('page.thanks');

            return;
        }

        $this->validate();

        if (! $this->passesTurnstile()) {
            return;
        }

        $data = $this->contactData();

        // Without a database, the mail to the agency is the only copy of the
        // inquiry. If it fails, the sender has to know.
        try {
            Mail::to(self::recipients())->send(new ContactInquiry($data));
        } catch (\Throwable $e) {
            report($e);
            $this->addError('send', 'Ihre Anfrage konnte nicht gesendet werden. Bitte versuchen Sie es später erneut oder schreiben Sie uns an '.config('site.marketer.email').'.');

            return;
        }

        try {
            Mail::to($data['email'])->send(new ContactConfirmation);
        } catch (\Throwable $e) {
            report($e);
        }

        $this->resetContactFields();
        $this->redirectRoute('page.thanks');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
