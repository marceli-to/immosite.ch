<?php

/*
|--------------------------------------------------------------------------
| Map (module:map)
|--------------------------------------------------------------------------
|
| Mapbox map with markers. The token is public (pk.…) but still belongs in
| .env and must be restricted to the domain in the Mapbox account
| (go-live checklist). Without a token the area stays empty, with no JS error.
|
*/

return [

    'token' => env('MAPBOX_TOKEN'),

    // Empty in .env = default style.
    'style' => env('MAPBOX_STYLE') ?: 'mapbox://styles/marcelitoooo/ck16ms7m51nlo1cmwnqrbjuyq?optimize=true',

    // [longitude, latitude] – order as in Mapbox, not as in Google.
    'center' => [8.541694, 47.376887],

    'zoom' => 15,

    // The first marker is the project. More e.g. for station, school.
    'markers' => [
        ['coordinates' => [8.541694, 47.376887], 'title' => 'Projekt'],
    ],

];
