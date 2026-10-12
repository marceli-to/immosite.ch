<?php

namespace App\Apartments;

use Illuminate\Support\Collection;

/**
 * Entry point for views: loads apartments from the configured driver and
 * prepares order, filter options and grouping from config/apartments.php.
 */
class ApartmentRepository
{
    public function source(): Source
    {
        $driver = config('apartments.driver');
        $class = config("apartments.drivers.{$driver}");

        if (! $class || ! class_exists($class)) {
            throw new \RuntimeException("Apartment driver '{$driver}' is not installed (config/apartments.php).");
        }

        return app($class);
    }

    /** @return Collection<int, Apartment> */
    public function all(): Collection
    {
        $types = config('apartments.types');

        $apartments = collect($this->source()->all())
            ->filter(fn (Apartment $a) => ! $types || in_array($a->type, $types, true))
            ->values();

        if (config('apartments.sort') === 'natural') {
            $floorOrder = array_flip(array_keys(config('apartments.floors')));

            $apartments = $apartments->sort(function (Apartment $a, Apartment $b) use ($floorOrder) {
                return strnatcmp($a->house ?? '', $b->house ?? '')
                    ?: ($floorOrder[$a->floor] ?? PHP_INT_MAX) <=> ($floorOrder[$b->floor] ?? PHP_INT_MAX)
                    ?: strnatcmp($a->ref, $b->ref);
            })->values();
        }

        return $apartments;
    }

    public function floorLabel(?string $floor): ?string
    {
        return $floor === null ? null : (config("apartments.floors.{$floor}") ?? $floor);
    }

    public function houseLabel(?string $house): ?string
    {
        return $house === null ? null : (config("apartments.houses.{$house}") ?? "Haus {$house}");
    }

    /**
     * Options per filter, built from the data so option values always match the
     * rows' data attributes. Only values that occur are offered.
     *
     * @return array<string, array{label: string, options: array<string, string>}>
     */
    public function filterOptions(Collection $apartments): array
    {
        $floorOrder = array_flip(array_keys(config('apartments.floors')));
        $filters = [];

        foreach (config('apartments.filters') as $filter) {
            $filters[$filter] = match ($filter) {
                'status' => [
                    'label' => config('apartments.filter_labels.status', 'Verfügbarkeit'),
                    'options' => collect(Status::cases())
                        ->filter(fn (Status $s) => $apartments->contains(fn (Apartment $a) => $a->status === $s))
                        ->mapWithKeys(fn (Status $s) => [$s->value => $s->label()])
                        ->all(),
                ],
                'rooms' => [
                    'label' => config('apartments.filter_labels.rooms', 'Zimmer'),
                    'options' => $apartments->filter(fn (Apartment $a) => $a->rooms !== null)
                        ->sortBy('rooms')
                        ->mapWithKeys(fn (Apartment $a) => [$a->roomsLabel() => $a->roomsLabel().' Zimmer'])
                        ->all(),
                ],
                'floor' => [
                    'label' => config('apartments.filter_labels.floor', 'Etage'),
                    'options' => $apartments->pluck('floor')->filter()->unique()
                        ->sortBy(fn ($floor) => $floorOrder[$floor] ?? PHP_INT_MAX)
                        ->mapWithKeys(fn ($floor) => [$floor => $this->floorLabel($floor)])
                        ->all(),
                ],
                'house' => [
                    'label' => config('apartments.filter_labels.house', 'Haus'),
                    'options' => $apartments->pluck('house')->filter()->unique()->sort(SORT_NATURAL)
                        ->mapWithKeys(fn ($house) => [$house => $this->houseLabel($house)])
                        ->all(),
                ],
            };
        }

        return $filters;
    }

    /**
     * Tables to render: one per house when grouping is on, otherwise one.
     *
     * @return Collection<string, Collection<int, Apartment>> heading => apartments
     */
    public function groups(Collection $apartments): Collection
    {
        if (! config('apartments.group_by_house')) {
            return collect(['' => $apartments]);
        }

        return $apartments->groupBy(fn (Apartment $a) => $this->houseLabel($a->house) ?? '');
    }
}
