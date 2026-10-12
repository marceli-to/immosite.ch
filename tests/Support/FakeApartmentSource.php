<?php

namespace Tests\Support;

use App\Apartments\Apartment;
use App\Apartments\Source;

/** Apartments from config('testing.apartments'), so module tests don't depend on an installed driver. */
class FakeApartmentSource implements Source
{
    public function all(): array
    {
        return array_map(fn (array $row) => Apartment::fromArray($row), config('testing.apartments', []));
    }
}
