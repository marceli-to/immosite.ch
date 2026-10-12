<?php

/*
|--------------------------------------------------------------------------
| Downloads (module:downloads)
|--------------------------------------------------------------------------
|
| PDF buttons per group: [label, file under public/]. A file that does not
| exist yet shows a disabled "PDF folgt" button instead of a dead link, so the
| site can go live before all documents are in.
|
*/

return [

    'building-spec' => [
        ['Kurzbaubeschrieb', 'downloads/kurzbaubeschrieb.pdf'],
    ],

    'apartments' => [
        ['Facts & Figures', 'downloads/facts-figures.pdf'],
        ['Umgebungsplan', 'downloads/umgebungsplan.pdf'],
    ],

];
