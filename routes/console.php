<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('uam:expire-accounts')->daily();
Schedule::command('uam:password-expiry-notify')->daily();
