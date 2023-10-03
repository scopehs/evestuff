<?php

namespace App\Listeners;

use App\Events\StationSheetMessageUpdate;

class SendStationSheetMessageUpdate
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StationSheetMessageUpdate $event): void
    {
        //
    }
}
