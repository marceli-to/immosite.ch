<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Notifications\WeeklyRegistrationsExport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ExportWeeklyRegistrations extends Command
{
    protected $signature = 'registrations:export-weekly {--all : All registrations, not only those not yet sent}';

    protected $description = 'Sends the new registrations as a list to the marketer and marks them as sent';

    public function handle(): int
    {
        $recipients = self::addresses(config('registration-export.recipients'));

        if ($recipients === []) {
            $this->error('No recipient set (REGISTRATIONS_EXPORT_EMAIL).');

            return self::FAILURE;
        }

        $query = Registration::query()->orderBy('created_at');

        if (! $this->option('all')) {
            $query->whereNull('exported_at');
        }

        $registrations = $query->get();

        // An empty week is reported too: that way the agency sees that the export
        // is running and hasn't simply stopped.
        Notification::route('mail', $recipients)
            ->notify(new WeeklyRegistrationsExport($registrations, now()->subWeek(), now()));

        // Only mark after a successful send; if the mail fails, the registrations
        // go out again next week.
        if ($registrations->isNotEmpty()) {
            Registration::whereKey($registrations->modelKeys())->update(['exported_at' => now()]);
        }

        $this->info("Sent {$registrations->count()} registration(s) to ".implode(', ', $recipients).'.');

        return self::SUCCESS;
    }

    /** @return array<int,string> */
    public static function addresses(?string $list): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $list))));
    }
}
