<?php

namespace App\Listeners;

use App\Events\StationNotificationNew;

class SendStationNotificationNew
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
     * @param  StationNotificationNew  $event
     * @return void
     */
    public function handle(StationNotificationNew $event)
    {
        //
    }
}
