<?php

/*
|--------------------------------------------------------------------------
| Isometry (module:isometry)
|--------------------------------------------------------------------------
|
| Interactive building drawing next to the apartment table. Built from the
| designer's Illustrator SVG export with `php artisan isometry:build`; see
| resources/isometry/README.md for what the AI file has to look like.
|
*/

return [

    // Illustrator SVG export (input) and the cleaned drawing (output, committed).
    'source' => resource_path('isometry/source.svg'),
    'output' => resource_path('isometry/building.svg'),

    // Floor layers in the AI file (layer name as in Illustrator) => apartment floor
    // value (Apartment::$floor, the keys of config apartments.floors). Bottom to top.
    'floors' => [
        'EG' => 'EG',
        '1. OG' => '1',
        '2. OG' => '2',
    ],

    // Layer with surroundings (ground, trees); stays still. null = none.
    'base' => 'Umgebung',

    // Group/shape names inside a floor that are apartments, and how they become the
    // apartment ref. Default: "A.01" or "V101" style names → "A.01" / "V.101".
    // Names that don't match (Gang, Lift, Treppe) stay passive.
    'ref_pattern' => '/^([A-Z]{1,3})\.?(\d{1,4})$/',
    'ref_format' => '$1.$2',

    // Fill colours the designer used for side faces and top faces. The build marks
    // these shapes so CSS can colour them by status.
    'face_fill' => '#d0dde0',
    'top_fill' => '#e0e9eb',

    // How far floors above the selected one are lifted, in SVG user units
    // (about 2 floor heights).
    'lift' => 180,

    'label' => 'Gebäude mit Wohnungen nach Etage',

];
