<?php

/*
|--------------------------------------------------------------------------
| Lage (module:pages-full)
|--------------------------------------------------------------------------
|
| Distanztabelle und Google-Maps-Link der Seite /lage. Zeiten in Minuten,
| null = Feld bleibt leer. Spalten in der Reihenfolge von 'modes'.
|
*/

return [

    'google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=Z%C3%BCrich+HB',

    'modes' => [
        'walk' => 'Zu Fuss',
        'bike' => 'Velo',
        'transit' => 'ÖV',
        'car' => 'Auto',
    ],

    'distances' => [
        ['destination' => 'Bahnhof', 'walk' => 8, 'bike' => 3, 'transit' => 4, 'car' => 4],
        ['destination' => 'Einkaufen', 'walk' => 5, 'bike' => 2, 'transit' => null, 'car' => 2],
        ['destination' => 'Schule', 'walk' => 10, 'bike' => 4, 'transit' => 6, 'car' => 5],
        ['destination' => 'Autobahnanschluss', 'walk' => null, 'bike' => null, 'transit' => null, 'car' => 7],
    ],

];
