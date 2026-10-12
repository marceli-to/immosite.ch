<?php

/*
|--------------------------------------------------------------------------
| Melon / emonitor v2 (module:apartments-melon)
|--------------------------------------------------------------------------
|
| Flat JSON array of objects. MELON_MOCK=true serves database/data/melon-mock.json
| instead (while the agency has not opened the endpoint yet, and for tests).
|
*/

return [

    'api_url' => env('MELON_API_URL'),

    'mock' => (bool) env('MELON_MOCK', false),

    'timeout' => 15,

    // Melon object_type.key => our type (config apartments.types filters on it).
    'types' => [
        'apartment' => 'apartment',
        'attica_apartment' => 'apartment',
        'maisonette' => 'apartment',
        'studio' => 'apartment',
        'penthouse' => 'apartment',
        'loft' => 'apartment',
        'office' => 'commercial',
        'retail' => 'commercial',
        'parking_space' => 'parking',
    ],

];
