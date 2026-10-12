<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Notifications\WeeklyRegistrationsExport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ExportWeeklyRegistrations extends Command
{
    protected $signature = 'registrations:export-weekly {--all : Alle Anmeldungen, nicht nur die noch nicht verschickten}';

    protected $description = 'Schickt die neuen Anmeldungen als Liste an die Vermarktung und markiert sie als verschickt';

    public function handle(): int
    {
        $recipients = self::addresses(config('registration-export.recipients'));

        if ($recipients === []) {
            $this->error('Kein Empfänger gesetzt (REGISTRATIONS_EXPORT_EMAIL).');

            return self::FAILURE;
        }

        $query = Registration::query()->orderBy('created_at');

        if (! $this->option('all')) {
            $query->whereNull('exported_at');
        }

        $registrations = $query->get();

        // Auch eine leere Woche wird gemeldet: so sieht die Vermarktung, dass der
        // Versand laeuft und nicht einfach ausgefallen ist.
        Notification::route('mail', $recipients)
            ->notify(new WeeklyRegistrationsExport($registrations, now()->subWeek(), now()));

        // Erst nach erfolgreichem Versand markieren; scheitert die Mail, gehen die
        // Anmeldungen naechste Woche erneut mit.
        if ($registrations->isNotEmpty()) {
            Registration::whereKey($registrations->modelKeys())->update(['exported_at' => now()]);
        }

        $this->info("{$registrations->count()} Anmeldung(en) an ".implode(', ', $recipients).' verschickt.');

        return self::SUCCESS;
    }

    /** @return array<int,string> */
    public static function addresses(?string $list): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $list))));
    }
}
