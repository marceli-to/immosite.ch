<?php

/*
|--------------------------------------------------------------------------
| Indexierung
|--------------------------------------------------------------------------
|
| Steuert, ob die Umgebung von Suchmaschinen indexiert werden darf.
| Auf Staging steht ROBOTS_NOINDEX=true in der .env, auf Live bleibt der
| Wert leer. Wirkt an zwei Stellen: die /robots.txt-Route liefert dann
| "Disallow: /", und jede Response bekommt den Header
| "X-Robots-Tag: noindex, nofollow" (der zaehlt fuer bereits bekannte URLs).
|
*/

return [

    'noindex' => (bool) env('ROBOTS_NOINDEX', false),

];
