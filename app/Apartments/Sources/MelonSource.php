<?php

namespace App\Apartments\Sources;

use App\Apartments\ApiSource;
use App\Apartments\Apartment;
use Illuminate\Support\Facades\Http;

/** Melon / emonitor v2 object feed (module:apartments-melon). */
class MelonSource extends ApiSource
{
    public function fetch(): array
    {
        if (config('melon.mock')) {
            return $this->normalise(json_decode(file_get_contents(database_path('data/melon-mock.json')), true));
        }

        $url = config('melon.api_url');

        if (blank($url)) {
            throw new \RuntimeException('MELON_API_URL is not set.');
        }

        return $this->normalise(Http::timeout(config('melon.timeout'))->acceptJson()->get($url)->throw()->json() ?? []);
    }

    /** @return array<int, Apartment> */
    public function normalise(array $objects): array
    {
        return array_map(fn (array $o) => Apartment::fromArray([
            'ref' => $o['title'] ?? $o['reference_number'],
            'status' => match ($o['object_state'] ?? null) {
                'reserved' => 'reserved',
                'rented', 'taken', 'sold' => 'rented',
                default => 'free',
            },
            'house' => $o['building'] ?? null,
            'floor' => $o['floor'] ?? null,
            'rooms' => $o['rooms'] ?? null,
            'area' => $o['area'] ?? null,
            'outdoor_area' => collect([$o['balcony_area'] ?? null, $o['loggia_area'] ?? null, $o['terrace_area'] ?? null])->filter()->sum() ?: null,
            'rent_net' => isset($o['rentalgross'], $o['incidentals']) ? $o['rentalgross'] - $o['incidentals'] : null,
            'charges' => $o['incidentals'] ?? null,
            'rent_gross' => $o['rentalgross'] ?? null,
            'plan_url' => $o['layout_plan'] ?? null,
            'apply_url' => $o['application_form'] ?? null,
            'available_from' => $o['move_in_date'] ?? null,
            'type' => config('melon.types.'.($o['object_type']['key'] ?? ''), 'other'),
        ]), array_values($objects));
    }
}
