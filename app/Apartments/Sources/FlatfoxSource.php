<?php

namespace App\Apartments\Sources;

use App\Apartments\ApiSource;
use App\Apartments\Apartment;
use App\Apartments\Status;
use Illuminate\Support\Facades\Http;

/**
 * Flatfox public listing API (module:apartments-flatfox).
 *
 * Status: pre/act/dis are published (reserved flag decides free vs reserved),
 * arc/rem are archived/removed = rented. A listing can appear twice (re-published);
 * the one with the higher pk wins. Collective listings (config exclude_*) are dropped.
 */
class FlatfoxSource extends ApiSource
{
    private const ACTIVE = ['pre', 'act', 'dis'];

    public function fetch(): array
    {
        $url = config('flatfox.api_url');

        if (blank($url)) {
            throw new \RuntimeException('FLATFOX_API_URL is not set.');
        }

        $listings = [];

        // Follow `next` until the last page; cap guards against a looping API.
        for ($page = 0; $url && $page < 50; $page++) {
            $json = Http::timeout(config('flatfox.timeout'))->acceptJson()->get($url)->throw()->json();
            $listings = [...$listings, ...($json['results'] ?? [])];
            $url = $json['next'] ?? null;
        }

        return $this->normalise($listings);
    }

    /** @return array<int, Apartment> */
    public function normalise(array $listings): array
    {
        $latest = collect($listings)
            ->sortByDesc('pk')
            ->unique(fn ($l) => filled($l['reference'] ?? null) ? $l['reference'] : $l['pk'])
            ->sortBy('pk')
            ->reject(fn ($l) => in_array((string) ($l['ref_house'] ?? ''), config('flatfox.exclude_houses', []), true)
                || in_array((string) ($l['reference'] ?? ''), config('flatfox.exclude_refs', []), true));

        return $latest->map(fn (array $l) => Apartment::fromArray([
            'ref' => $this->ref($l),
            'status' => $this->status($l)->value,
            'house' => $l['ref_house'] ?? null,
            'floor' => isset($l['floor']) ? $this->floor($l['floor']) : null,
            'rooms' => $l['number_of_rooms'] ?? null,
            'area' => $l['surface_living'] ?? null,
            'outdoor_area' => $l['surface_balcony'] ?? null,
            'rent_net' => $l['rent_net'] ?? null,
            'charges' => $l['rent_charges'] ?? null,
            'rent_gross' => $l['rent_gross'] ?? $l['price_display'] ?? null,
            'price' => ($l['offer_type'] ?? null) === 'SALE' ? ($l['price_display'] ?? null) : null,
            'plan_url' => $this->plan($l),
            'apply_url' => isset($l['short_url']) ? 'https://flatfox.ch/de/listing'.$l['short_url'].'submit/' : null,
            'available_from' => $l['moving_date'] ?? null,
            'type' => config('flatfox.categories.'.($l['object_category'] ?? ''), 'other'),
        ]))->values()->all();
    }

    private function status(array $listing): Status
    {
        if (! in_array($listing['status'] ?? null, self::ACTIVE, true)) {
            return Status::Rented;
        }

        return ($listing['reserved'] ?? false) ? Status::Reserved : Status::Free;
    }

    private function ref(array $listing): string
    {
        $ref = strtr(config('flatfox.ref_format'), [
            '{ref_house}' => $listing['ref_house'] ?? '',
            '{ref_object}' => $listing['ref_object'] ?? '',
            '{reference}' => $listing['reference'] ?? '',
        ]);

        return trim($ref, '- ') !== '' ? $ref : (string) $listing['pk'];
    }

    /** Flatfox floors are numbers (0 = EG, -1 = UG); config apartments.floors uses these keys. */
    private function floor(int|string $floor): string
    {
        return match ((int) $floor) {
            0 => 'EG',
            -1 => 'UG',
            default => (string) (int) $floor,
        };
    }

    private function plan(array $listing): ?string
    {
        foreach ($listing['documents'] ?? [] as $document) {
            if (is_array($document) && filled($document['url'] ?? null)) {
                return $document['url'];
            }
        }

        return null;
    }
}
