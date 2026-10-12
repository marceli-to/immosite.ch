<?php

/*
|--------------------------------------------------------------------------
| Weekly list of registrations (module:weekly-export)
|--------------------------------------------------------------------------
|
| New registrations go to the marketer once a week as a mail with CSV.
| The schedule lives here instead of in code, so a test run isn't set up
| "temporarily" in code and then forgotten.
|
*/

return [

    // Multiple addresses, comma-separated.
    'recipients' => env('REGISTRATIONS_EXPORT_EMAIL'),
    'bcc' => env('REGISTRATIONS_EXPORT_BCC'),

    // 0 = Sunday, 1 = Monday … (Europe/Zurich)
    'day' => (int) env('REGISTRATIONS_EXPORT_DAY', 1),
    'time' => env('REGISTRATIONS_EXPORT_TIME', '08:00'),

];
