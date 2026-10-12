<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Anfrage aus dem Kontaktformular an die Vermarktung. "Antworten" geht an den Absender. */
class ContactInquiry extends Mailable
{
    use Queueable, SerializesModels;

    /** @param  array<string, string|null>  $data */
    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kontaktanfrage '.config('site.name').' – '.trim($this->data['first_name'].' '.$this->data['last_name']),
            replyTo: [new Address($this->data['email'], trim($this->data['first_name'].' '.$this->data['last_name']))],
        );
    }

    public function content(): Content
    {
        $labels = [
            'first_name' => 'Vorname', 'last_name' => 'Name', 'street' => 'Strasse/Nr.', 'zip_city' => 'PLZ/Ort',
            'email' => 'E-Mail', 'phone' => 'Telefon', 'message' => 'Nachricht',
        ];

        return new Content(
            markdown: 'mail.contact-inquiry',
            with: [
                'rows' => collect($labels)
                    ->mapWithKeys(fn ($label, $key) => [$label => $this->data[$key] ?? null])
                    ->filter(fn ($value) => filled($value)),
            ],
        );
    }
}
