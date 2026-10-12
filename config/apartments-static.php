<?php

/*
|--------------------------------------------------------------------------
| Static apartment data (module:apartments-static)
|--------------------------------------------------------------------------
|
| Copied from the Mieterspiegel, in its order (the table shows them exactly
| like this). Keys: see App\Apartments\Apartment::fromArray().
| status: free | reserved | rented. plan_url: path under public/ or null.
|
*/

return [

    ['ref' => 'A.01', 'house' => 'A', 'floor' => 'EG', 'rooms' => 2.5, 'area' => 61, 'outdoor_area' => 14, 'rent_net' => 1890, 'charges' => 180, 'rent_gross' => 2070, 'status' => 'free', 'plan_url' => null],
    ['ref' => 'A.02', 'house' => 'A', 'floor' => 'EG', 'rooms' => 3.5, 'area' => 84, 'outdoor_area' => 18, 'rent_net' => 2450, 'charges' => 220, 'rent_gross' => 2670, 'status' => 'reserved', 'plan_url' => null],
    ['ref' => 'A.11', 'house' => 'A', 'floor' => '1', 'rooms' => 3.5, 'area' => 86, 'outdoor_area' => 9, 'rent_net' => 2520, 'charges' => 220, 'rent_gross' => 2740, 'status' => 'free', 'plan_url' => null],
    ['ref' => 'A.12', 'house' => 'A', 'floor' => '1', 'rooms' => 4.5, 'area' => 108, 'outdoor_area' => 12, 'rent_net' => 3100, 'charges' => 260, 'rent_gross' => 3360, 'status' => 'rented', 'plan_url' => null],
    ['ref' => 'A.21', 'house' => 'A', 'floor' => '2', 'rooms' => 4.5, 'area' => 110, 'outdoor_area' => 12, 'rent_net' => 3180, 'charges' => 260, 'rent_gross' => 3440, 'status' => 'free', 'plan_url' => null],
    ['ref' => 'B.01', 'house' => 'B', 'floor' => 'EG', 'rooms' => 1.5, 'area' => 42, 'outdoor_area' => null, 'rent_net' => 1420, 'charges' => 140, 'rent_gross' => 1560, 'status' => 'free', 'plan_url' => null],
    ['ref' => 'B.11', 'house' => 'B', 'floor' => '1', 'rooms' => 2.5, 'area' => 63, 'outdoor_area' => 8, 'rent_net' => 1960, 'charges' => 180, 'rent_gross' => 2140, 'status' => 'free', 'plan_url' => null],
    ['ref' => 'B.21', 'house' => 'B', 'floor' => '2', 'rooms' => 5.5, 'area' => 132, 'outdoor_area' => 24, 'rent_net' => 3890, 'charges' => 300, 'rent_gross' => 4190, 'status' => 'reserved', 'plan_url' => null],

];
