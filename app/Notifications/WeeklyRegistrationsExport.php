<?php

namespace App\Notifications;

use App\Console\Commands\ExportWeeklyRegistrations;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class WeeklyRegistrationsExport extends Notification
{
    use Queueable;

    /**
     * @param  Collection<int, Registration>  $registrations
     */
    public function __construct(
        public Collection $registrations,
        public \DateTimeInterface $periodStart,
        public \DateTimeInterface $periodEnd,
    ) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $period = $this->periodStart->format('d.m.Y').' – '.$this->periodEnd->format('d.m.Y');

        $mail = (new MailMessage)
            ->subject(config('site.name').' – Neue Anmeldungen ('.$period.')')
            ->markdown('mail.registrations-export', [
                'registrations' => $this->registrations,
                'period' => $period,
            ]);

        if ($bcc = ExportWeeklyRegistrations::addresses(config('registration-export.bcc'))) {
            $mail->bcc($bcc);
        }

        if ($this->registrations->isNotEmpty()) {
            $filename = 'anmeldungen-'.$this->periodStart->format('Y-m-d').'-'.$this->periodEnd->format('Y-m-d').'.csv';
            $mail->attachData($this->csv(), $filename, ['mime' => 'text/csv']);
        }

        return $mail;
    }

    /** UTF-8 CSV with BOM and semicolons, so Excel (de-CH) reads umlauts and columns correctly. */
    public function csv(): string
    {
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, ['Datum', 'Vorname', 'Name', 'Strasse/Nr.', 'PLZ/Ort', 'E-Mail', 'Telefon', 'Interesse', 'Nachricht'], ';', '"', '');

        foreach ($this->registrations as $registration) {
            fputcsv($handle, [
                $registration->created_at->timezone('Europe/Zurich')->format('d.m.Y H:i'),
                $registration->first_name,
                $registration->last_name,
                $registration->street,
                $registration->zip_city,
                $registration->email,
                $registration->phone,
                implode(', ', $registration->interestLabels()),
                $registration->message,
            ], ';', '"', '');
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }
}
