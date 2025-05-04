<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

    // انشاء سجل الى جدول المتأخرات
    // Schedule::command('app:create-late-fee')->everyMinute();

    Schedule::command('app:send-rental-reminders')->everyMinute();





