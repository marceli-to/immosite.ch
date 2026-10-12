<?php

use App\Apartments\Sources\FlatfoxSource;
use App\Apartments\Sources\MelonSource;
use App\Apartments\Sources\StaticSource;

/*
|--------------------------------------------------------------------------
| Apartment listing (module:apartments)
|--------------------------------------------------------------------------
|
| Page /wohnen: filters + table(s). Columns, filters and grouping differ per
| project and are set here instead of rebuilding the table each time.
| The data comes from a driver (module apartments-static / -flatfox / -melon),
| always in the same normalised format (App\Apartments\Apartment).
|
*/

return [

    'driver' => env('APARTMENTS_DRIVER', 'static'),

    // One line per installed driver.
    'drivers' => [
        'static' => StaticSource::class, // module:apartments-static
        'flatfox' => FlatfoxSource::class, // module:apartments-flatfox
        'melon' => MelonSource::class, // module:apartments-melon
    ],

    // Which object types are listed (Flatfox: object_category, Melon: object_type).
    'types' => ['apartment'],

    // 'source': order as delivered (static = Mieterspiegel order, agencies want
    // exactly that). 'natural': house, floor, ref – for APIs with random order.
    'sort' => 'source',

    // Floors in display order: source value => label. Values not listed are
    // shown as they come and sorted last.
    'floors' => [
        'UG' => 'UG',
        'EG' => 'EG',
        '1' => '1. OG',
        '2' => '2. OG',
        '3' => '3. OG',
        '4' => '4. OG',
        'DG' => 'Attika',
    ],

    // Group the table by house. null = one table.
    // 'houses' => source value => heading.
    'group_by_house' => false,
    'houses' => [
        // 'A' => 'Haus A – Musterstrasse 1',
    ],

    // Table columns in order: key => [label, short label below md].
    // Available keys: ref, house, floor, rooms, area, outdoor_area, rent_net,
    // charges, rent_gross, price, available_from, plan, status.
    'columns' => [
        'ref' => ['Nr.', 'Nr.'],
        'floor' => ['Etage', 'Etage'],
        'rooms' => ['Zimmer', 'Zi.'],
        'area' => ['Wohnfläche m²', 'Fläche m²'],
        'outdoor_area' => ['Aussenfläche m²', 'Aussen m²'],
        'rent_net' => ['Nettomiete', 'Netto'],
        'charges' => ['Nebenkosten', 'NK'],
        'rent_gross' => ['Bruttomiete', 'Brutto'],
        'plan' => ['Grundriss', 'Plan'],
        'status' => ['Status', 'Status'],
    ],

    // Filters above the table, in order. Available: status, rooms, floor, house.
    'filters' => ['status', 'rooms', 'floor'],

    // Rented apartments show no rent (agencies don't publish it).
    'hide_prices_when_rented' => true,

    'status_labels' => [
        'free' => 'frei',
        'reserved' => 'reserviert',
        'rented' => 'vermietet',
    ],

    'footnote' => 'Alle Kosten in CHF pro Monat.',

];
