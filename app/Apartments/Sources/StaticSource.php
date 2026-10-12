<?php

namespace App\Apartments\Sources;

use App\Apartments\Apartment;
use App\Apartments\Source;

/** Apartments maintained by hand in config/apartments-static.php (module:apartments-static). */
class StaticSource implements Source
{
    public function all(): array
    {
        return array_map(fn (array $row) => Apartment::fromArray($row), config('apartments-static', []));
    }
}
