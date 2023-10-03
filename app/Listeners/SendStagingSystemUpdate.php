<?php

namespace App\Listeners;

use App\Events\StagingSystemUpdate;

class SendStagingSystemUpdate
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
    public function handle(StagingSystemUpdate $event): void
    {
        //
    }
}
