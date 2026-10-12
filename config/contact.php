<?php

/*
|--------------------------------------------------------------------------
| Contact form (module:contact-form)
|--------------------------------------------------------------------------
|
| Inquiries are not stored but mailed directly to the marketer
| (replies go to the sender via Reply-To). The sender gets a confirmation
| and ends up on /danke.
|
*/

return [

    // Multiple addresses comma-separated. Empty = marketer address from config/site.php.
    'recipients' => env('CONTACT_RECIPIENTS'),

    // 'required', 'optional' or false (not shown).
    'fields' => [
        'street' => 'required',
        'zip_city' => 'required',
        'phone' => 'required',
        'message' => 'optional',
    ],

];
