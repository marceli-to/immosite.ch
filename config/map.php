<?php

/*
|--------------------------------------------------------------------------
| Karte (module:map)
|--------------------------------------------------------------------------
|
| Mapbox-Karte mit Markern. Der Token ist oeffentlich (pk.…), gehoert aber in
| die .env und muss bei Mapbox auf die Domain eingeschraenkt werden
| (Go-live-Checkliste). Ohne Token bleibt die Flaeche leer, ohne JS-Fehler.
|
*/

return [

    'token' => env('MAPBOX_TOKEN'),

    // Leer in der .env = Standard-Stil.
    'style' => env('MAPBOX_STYLE') ?: 'mapbox://styles/marcelitoooo/ck16ms7m51nlo1cmwnqrbjuyq?optimize=true',

    // [Laengengrad, Breitengrad] – Reihenfolge wie bei Mapbox, nicht wie bei Google.
    'center' => [8.541694, 47.376887],

    'zoom' => 15,

    // Der erste Marker ist das Projekt. Weitere z.B. fuer Bahnhof, Schule.
    'markers' => [
        ['coordinates' => [8.541694, 47.376887], 'title' => 'Projekt'],
    ],

    // Link "In Google Maps öffnen" (Lage-Abschnitt). Null = kein Link.
    'google_maps_url' => null,

];
