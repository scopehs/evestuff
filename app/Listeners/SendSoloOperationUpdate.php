<?php

namespace App\Listeners;

use App\Events\SoloOperationUpdate;

class SendSoloOperationUpdate
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
     * @param  \App\Events\SoloOperationUpdate  $event
     * @return void
     */
    public function handle(SoloOperationUpdate $event)
    {
        //
    }
}
