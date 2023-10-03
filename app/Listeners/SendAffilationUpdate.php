<?php

namespace App\Listeners;

use App\Events\AffilationUpdate;

class SendAffilationUpdate
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
    public function handle(AffilationUpdate $event): void
    {
        //
    }
}
