<?php

namespace App\Listeners;

use App\Events\OperationUpdate;

class SendOperationUpdate
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
     * @param  \App\Events\OperationUpdate  $event
     * @return void
     */
    public function handle(OperationUpdate $event)
    {
        //
    }
}
