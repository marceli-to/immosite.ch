<?php

namespace App\Apartments\Sources;

use App\Apartments\Source;
use App\Models\CommercialObject;

/** Commercial units maintained in /admin (module:commercial-admin). */
class DatabaseCommercialSource implements Source
{
    public function all(): array
    {
        return CommercialObject::orderBy('position')->orderBy('id')->get()
            ->map(fn (CommercialObject $object) => $object->toApartment())
            ->all();
    }
}
