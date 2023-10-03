<?php

namespace App\Listeners;

use App\Events\FleetKeysUpdate;

class SendFleetKeysUpdate
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
     * @param  FleetKeysUpdate  $event
     * @return void
     */
    public function handle(FleetKeysUpdate $event)
    {
        //
    }
}
