<?php

namespace App\Listeners;

use App\Events\TowerMessageUpdate;

class SendTowerMessageUpdate
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
     * @param  TowerMessageUpdate  $event
     * @return void
     */
    public function handle(TowerMessageUpdate $event)
    {
        //
    }
}
