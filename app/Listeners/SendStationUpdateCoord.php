<?php

namespace App\Listeners;

use App\Events\StationUpdateCoord;

class SendStationUpdateCoord
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  StationUpdateCoord  $event
     * @return void
     */
    public function handle(StationUpdateCoord $event)
    {
        //
    }
}
