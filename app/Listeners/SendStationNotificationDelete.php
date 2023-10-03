<?php

namespace App\Listeners;

use App\Events\StationNotificationDelete;

class SendStationNotificationDelete
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
     * @param  StationNotificationDelete  $event
     * @return void
     */
    public function handle(StationNotificationDelete $event)
    {
        //
    }
}
