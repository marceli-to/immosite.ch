<?php

/*
|--------------------------------------------------------------------------
| Flatfox (module:apartments-flatfox)
|--------------------------------------------------------------------------
|
| Public listing API of the agency, e.g.
| https://flatfox.ch/api/v1/public-listing/?organization=1234&expand=documents
| Paged (`next`). Fetched by `apartments:fetch` every 15 minutes.
|
*/

return [

    'api_url' => env('FLATFOX_API_URL'),

    'timeout' => 15,

    // Flatfox object_category => our type (config apartments.types filters on it).
    'categories' => [
        'APARTMENT' => 'apartment',
        'HOUSE' => 'apartment',
        'SHARED' => 'apartment',
        'INDUSTRY' => 'commercial',
        'PARK' => 'parking',
        'GASTRO' => 'commercial',
        'SECONDARY' => 'other',
    ],

    // Reference shown in the table. {ref_house} / {ref_object} / {reference}.
    'ref_format' => '{ref_house}-{ref_object}',

];
