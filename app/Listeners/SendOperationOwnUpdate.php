<?php

namespace App\Listeners;

use App\Events\OperationOwnUpdate;

class SendOperationOwnUpdate
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
     * @param  \App\Events\OperationOwnUpdate  $event
     * @return void
     */
    public function handle(OperationOwnUpdate $event)
    {
        //
    }
}
