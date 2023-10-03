<?php

namespace App\Listeners;

use App\Events\StationLogUpdate;

class SendStationLogUpdate
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
     * @param  vent=StationLogUpdate  $event
     * @return void
     */
    public function handle(StationLogUpdate $event)
    {
        //
    }
}
