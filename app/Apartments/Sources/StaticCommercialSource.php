<?php

namespace App\Apartments\Sources;

use App\Apartments\Apartment;
use App\Apartments\Source;

/** Commercial units from config/commercial.php (module:commercial). */
class StaticCommercialSource implements Source
{
    public function all(): array
    {
        return array_map(fn (array $row) => Apartment::fromArray($row + ['type' => 'commercial']), config('commercial.objects', []));
    }
}
