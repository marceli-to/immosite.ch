<?php

/*
|--------------------------------------------------------------------------
| Wochenliste der Anmeldungen (module:weekly-export)
|--------------------------------------------------------------------------
|
| Neue Anmeldungen gehen einmal pro Woche als Mail mit CSV an die Vermarktung.
| Zeitpunkt hier statt im Code, damit ein Testlauf nicht im Code "temporaer"
| umgestellt und dann vergessen wird.
|
*/

return [

    // Mehrere Adressen per Komma getrennt.
    'recipients' => env('REGISTRATIONS_EXPORT_EMAIL'),
    'bcc' => env('REGISTRATIONS_EXPORT_BCC'),

    // 0 = Sonntag, 1 = Montag … (Europe/Zurich)
    'day' => (int) env('REGISTRATIONS_EXPORT_DAY', 1),
    'time' => env('REGISTRATIONS_EXPORT_TIME', '08:00'),

];
