<?php

use App\Jobs\ParsePost;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ParsePost, 'parsing')->cron('*/' . config('parsing.period') . ' * * * *');
