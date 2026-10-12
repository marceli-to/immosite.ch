<?php

/*
|--------------------------------------------------------------------------
| Melon lead hook (module:melon-lead-hook)
|--------------------------------------------------------------------------
|
| Contact and application form submissions are also sent to Melon as a lead
| (external-data-hook, JSON POST with Basic Auth). Best effort: errors are
| logged and never shown to the visitor; the mails are already sent.
|
*/

return [

    'enabled' => (bool) env('MELON_HOOK_ENABLED', false),
    'url' => env('MELON_HOOK_URL'),
    'username' => env('MELON_HOOK_USERNAME'),
    'password' => env('MELON_HOOK_PASSWORD'),
    'timeout' => (int) env('MELON_HOOK_TIMEOUT', 10),

];
