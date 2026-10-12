<?php

/*
|--------------------------------------------------------------------------
| Indexing
|--------------------------------------------------------------------------
|
| Controls whether search engines may index this environment.
| On staging, .env has ROBOTS_NOINDEX=true; on live the value stays
| empty. Applies in two places: the /robots.txt route then returns
| "Disallow: /", and every response gets the header
| "X-Robots-Tag: noindex, nofollow" (which counts for already known URLs).
|
*/

return [

    'noindex' => (bool) env('ROBOTS_NOINDEX', false),

];
