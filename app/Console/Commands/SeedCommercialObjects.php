<?php

namespace App\Console\Commands;

use App\Models\CommercialObject;
use Illuminate\Console\Command;

class SeedCommercialObjects extends Command
{
    protected $signature = 'commercial:seed {--force : Add missing objects even if the table is not empty}';

    protected $description = 'Copy the commercial units from config/commercial.php into the database (for /admin)';

    public function handle(): int
    {
        if (CommercialObject::exists() && ! $this->option('force')) {
            $this->warn('Table is not empty; existing objects stay as they are. Use --force to add missing ones.');

            return self::SUCCESS;
        }

        $added = 0;

        foreach (config('commercial.objects') as $position => $object) {
            $model = CommercialObject::firstOrCreate(
                ['ref' => $object['ref']],
                [
                    'position' => $position,
                    'house' => $object['house'] ?? null,
                    'floor' => $object['floor'] ?? null,
                    'area' => $object['area'] ?? null,
                    'rent_net' => $object['rent_net'] ?? null,
                    'charges' => $object['charges'] ?? null,
                    'status' => $object['status'] ?? 'free',
                    'plan_url' => $object['plan_url'] ?? null,
                ],
            );

            $added += $model->wasRecentlyCreated ? 1 : 0;
        }

        $this->info("Added {$added} object(s).");

        return self::SUCCESS;
    }
}
