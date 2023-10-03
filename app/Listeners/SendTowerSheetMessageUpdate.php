<?php

namespace App\Listeners;

use App\Events\TowerSheetMessageUpdate;

class SendTowerSheetMessageUpdate
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
    public function handle(TowerSheetMessageUpdate $event): void
    {
        //
    }
}
