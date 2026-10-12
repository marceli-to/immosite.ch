<?php

namespace App\Console\Commands;

use App\Apartments\ApartmentRepository;
use App\Apartments\ApiSource;
use Illuminate\Console\Command;

class FetchApartments extends Command
{
    protected $signature = 'apartments:fetch';

    protected $description = 'Fetch apartments from the configured API driver and cache them (keeps the last data on failure)';

    public function handle(ApartmentRepository $repository): int
    {
        $source = $repository->source();

        if (! $source instanceof ApiSource) {
            $this->info('Driver "'.config('apartments.driver').'" has no API, nothing to fetch.');

            return self::SUCCESS;
        }

        $count = $source->refresh();

        if ($count === null) {
            $this->error('Fetch failed or returned nothing, kept the last data (see log).');

            return self::FAILURE;
        }

        $this->info("Stored {$count} apartment(s).");

        return self::SUCCESS;
    }
}
