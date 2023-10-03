<?php

namespace App\Listeners;

use App\Events\StationNotificationUpdate;

class SendStationNotificationUpdate
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
     * @param  StationNotificationUpdate  $event
     * @return void
     */
    public function handle(StationNotificationUpdate $event)
    {
        //
    }
}
