<?php

namespace App\Listeners;

use App\Events\TowerDelete;

class SendTowerDelete
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
     * @param  TowerDelete  $event
     * @return void
     */
    public function handle(TowerDelete $event)
    {
        //
    }
}
