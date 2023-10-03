<?php

namespace App\Listeners;

use App\Events\ReconTimerUpdate;

class SendReconTimerUpdate
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
     * @param  ReconTimerUpdate  $event
     * @return void
     */
    public function handle(ReconTimerUpdate $event)
    {
        //
    }
}
