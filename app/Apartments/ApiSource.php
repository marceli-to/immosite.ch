<?php

namespace App\Apartments;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Base for API sources (Flatfox, Melon). `apartments:fetch` calls refresh() on a
 * schedule and stores the normalised result; page views only read that file.
 * A failed or empty fetch keeps the last good data instead of emptying the table.
 */
abstract class ApiSource implements Source
{
    public const CACHE_FILE = 'apartments.json';

    /**
     * Load and normalise the current data from the API.
     *
     * @return array<int, Apartment>
     *
     * @throws \Throwable when the API cannot be reached or answers with an error
     */
    abstract public function fetch(): array;

    public function all(): array
    {
        $disk = Storage::disk('local');

        // First request after deploy, before the scheduler has run once.
        if (! $disk->exists(self::CACHE_FILE)) {
            $this->refresh();
        }

        $rows = json_decode((string) $disk->get(self::CACHE_FILE), true) ?: [];

        return array_map(fn (array $row) => Apartment::fromArray($row), $rows);
    }

    /** @return int|null number of apartments stored, null if the fetch failed */
    public function refresh(): ?int
    {
        try {
            $apartments = $this->fetch();
        } catch (\Throwable $e) {
            Log::warning('Apartment fetch failed, keeping last data: '.$e->getMessage());

            return null;
        }

        if ($apartments === []) {
            Log::warning('Apartment fetch returned no apartments, keeping last data.');

            return null;
        }

        Storage::disk('local')->put(
            self::CACHE_FILE,
            json_encode(array_map(fn (Apartment $a) => $a->toArray(), $apartments), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        );

        return count($apartments);
    }
}
