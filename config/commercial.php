<?php

use App\Apartments\Sources\ApartmentsCommercialSource;
use App\Apartments\Sources\DatabaseCommercialSource;
use App\Apartments\Sources\StaticCommercialSource;

/*
|--------------------------------------------------------------------------
| Commercial units (module:commercial)
|--------------------------------------------------------------------------
|
| Page /gewerbe, same table as the apartments with its own columns.
| source: 'static'     = the list below
|         'apartments' = same driver as /wohnen (Flatfox/Melon), type 'commercial'
|         'database'   = maintained in /admin (module commercial-admin)
|
*/

return [

    'source' => env('COMMERCIAL_SOURCE', 'static'),

    'sources' => [
        'static' => StaticCommercialSource::class,
        'apartments' => ApartmentsCommercialSource::class,
        'database' => DatabaseCommercialSource::class, // module:commercial-admin
    ],

    // Keys: see App\Apartments\Apartment::fromArray(). Also the seed for commercial-admin.
    'objects' => [
        ['ref' => 'G.01', 'floor' => 'EG', 'area' => 203, 'rent_net' => 5400, 'charges' => 600, 'status' => 'free', 'type' => 'commercial'],
        ['ref' => 'G.02', 'floor' => 'UG', 'area' => 367, 'rent_net' => 4200, 'charges' => 500, 'status' => 'reserved', 'type' => 'commercial'],
    ],

    // Like apartments.columns.
    'columns' => [
        'ref' => ['Objekt', 'Objekt'],
        'floor' => ['Etage', 'Etage'],
        'area' => ['Fläche m²', 'Fläche m²'],
        'rent_net' => ['Nettomiete', 'Netto'],
        'charges' => ['Nebenkosten', 'NK'],
        'plan' => ['Grundriss', 'Plan'],
        'status' => ['Status', 'Status'],
    ],

];
