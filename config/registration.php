<?php

/*
|--------------------------------------------------------------------------
| Anmeldeformular (module:registration)
|--------------------------------------------------------------------------
|
| Felder und Auswahl pro Projekt. Die Unterschiede zwischen den Projekten
| (Nachricht ja/nein, Telefon Pflicht oder nicht) stehen hier statt im Code.
|
*/

return [

    // Auswahl "Ich interessiere mich für", Wert => Beschriftung, in Anzeigereihenfolge.
    // Leeres Array = Auswahl wird nicht angezeigt.
    'interests' => [
        '2.5' => '2.5-Zimmerwohnung',
        '3.5' => '3.5-Zimmerwohnung',
        '4.5' => '4.5-Zimmerwohnung',
        'gewerbe' => 'Gewerbefläche',
    ],

    // Vorname, Name und E-Mail sind immer Pflicht. Die übrigen Felder:
    // 'required', 'optional' oder false (nicht angezeigt).
    'fields' => [
        'street' => 'required',
        'zip_city' => 'required',
        'phone' => 'required',
        'message' => false,
    ],

    // Nach dem Absenden: null = Bestaetigung im Formular, sonst Routenname
    // (z.B. 'page.thanks'), damit die Anmeldung als eigene URL messbar ist.
    'redirect_route' => null,

    'confirmation' => [
        'subject' => 'Ihre Anmeldung – '.env('APP_NAME', 'Immosite'),
    ],

];
