<?php

/*
|--------------------------------------------------------------------------
| Kontaktformular (module:contact-form)
|--------------------------------------------------------------------------
|
| Anfragen werden nicht gespeichert, sondern direkt an die Vermarktung
| gemailt (Antwort geht per Reply-To an den Absender). Der Absender bekommt
| eine Bestaetigung und landet auf /danke.
|
*/

return [

    // Mehrere Adressen per Komma. Leer = Adresse der Vermarktung aus config/site.php.
    'recipients' => env('CONTACT_RECIPIENTS'),

    // 'required', 'optional' oder false (nicht angezeigt).
    'fields' => [
        'street' => 'required',
        'zip_city' => 'required',
        'phone' => 'required',
        'message' => 'optional',
    ],

];
