<?php

/*
|--------------------------------------------------------------------------
| Registration form (module:registration)
|--------------------------------------------------------------------------
|
| Fields and options per project. The differences between projects
| (message yes/no, phone required or not) live here instead of in code.
|
*/

return [

    // "Ich interessiere mich für" options, value => label, in display order.
    // Empty array = options are not shown.
    'interests' => [
        '2.5' => '2.5-Zimmerwohnung',
        '3.5' => '3.5-Zimmerwohnung',
        '4.5' => '4.5-Zimmerwohnung',
        'gewerbe' => 'Gewerbefläche',
    ],

    // First name, last name and email are always required. The other fields:
    // 'required', 'optional' or false (not shown).
    'fields' => [
        'street' => 'required',
        'zip_city' => 'required',
        'phone' => 'required',
        'message' => false,
    ],

    // After submit: null = confirmation inside the form, otherwise a route name
    // (e.g. 'page.thanks'), so the registration is trackable as its own URL.
    'redirect_route' => null,

    // Heading above the form and after submitting.
    'heading' => 'Anmeldung',
    'success_heading' => 'Vielen Dank für Ihre Anmeldung',
    'success_text' => 'Wir haben Ihre Angaben erhalten und informieren Sie, sobald die Vermietung startet.',

    // Mail to the marketer for every new registration (multiple addresses comma-separated).
    // Empty = off; the weekly export (module weekly-export) still sends the summary.
    'notify' => env('REGISTRATIONS_NOTIFY_EMAIL'),

    'confirmation' => [
        'subject' => 'Ihre Anmeldung – '.env('APP_NAME', 'Immosite'),
    ],

];
