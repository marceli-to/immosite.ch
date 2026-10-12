<?php

/*
|--------------------------------------------------------------------------
| Building specification / Kurzbaubeschrieb (module:building-spec)
|--------------------------------------------------------------------------
|
| Page /ausbau. Copy from the client's "Kurzbaubeschrieb" PDF: one section per
| heading, one line per entry. display: 'list' (all open, like Adlerstrasse)
| or 'accordion' (collapsed, for long specs).
|
*/

return [

    'display' => 'list',

    'sections' => [
        'Bodenbeläge' => [
            'Wohnräume: Parkett Eiche, matt versiegelt',
            'Nasszellen: Feinsteinzeugplatten',
        ],
        'Küche' => [
            'Arbeitsplatte: Granit',
            'Geräte: Glaskeramik, Dunstabzug, Geschirrspüler, Kühl- und Gefriergerät',
        ],
        'Badezimmer' => [
            'Sanitärapparate: Keramik weiss, Spiegelschrank',
            'Bodenebene Dusche oder Badewanne',
        ],
        'Waschen' => [
            'Waschmaschine und Tumbler in jeder Wohnung',
        ],
    ],

];
