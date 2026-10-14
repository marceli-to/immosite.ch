<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** New registration to the marketer, right away (the weekly export is the summary). "Reply" goes to the sender. */
class RegistrationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Neue Anmeldung '.config('site.name').' – '.$this->registration->fullName(),
            replyTo: [new Address($this->registration->email, $this->registration->fullName())],
        );
    }

    public function content(): Content
    {
        $r = $this->registration;

        return new Content(
            markdown: 'mail.registration-received',
            with: [
                'rows' => collect([
                    'Interesse' => implode(', ', $r->interestLabels()),
                    'Vorname' => $r->first_name,
                    'Name' => $r->last_name,
                    'Strasse/Nr.' => $r->street,
                    'PLZ/Ort' => $r->zip_city,
                    'E-Mail' => $r->email,
                    'Telefon' => $r->phone,
                    'Nachricht' => $r->message,
                ])->filter(fn ($value) => filled($value)),
            ],
        );
    }
}
