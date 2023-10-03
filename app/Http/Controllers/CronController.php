<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{
    public function notifications()
    {
        Artisan::call('update:notifications');
    }

    public function timers()
    {
        Artisan::call('update:timers');
    }

    public function alliances()
    {
        Artisan::call('update:alliances');
    }

    public function update()
    {
        Artisan::call('schedule:run');
    }
}
