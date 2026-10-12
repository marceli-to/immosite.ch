<?php

use Illuminate\Support\Facades\Schedule;

// Server cron: * * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1

Schedule::command('registrations:export-weekly')->weeklyOn(config('registration-export.day'), config('registration-export.time'))->timezone('Europe/Zurich'); // module:weekly-export
