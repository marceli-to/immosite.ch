<?php

namespace App\Apartments\Sources;

use App\Apartments\Apartment;
use App\Apartments\ApartmentRepository;
use App\Apartments\Source;

/** Commercial units from the apartments driver (Flatfox/Melon list them too) (module:commercial). */
class ApartmentsCommercialSource implements Source
{
    public function __construct(private ApartmentRepository $apartments) {}

    public function all(): array
    {
        return array_values(array_filter(
            $this->apartments->source()->all(),
            fn (Apartment $a) => $a->type === 'commercial',
        ));
    }
}
