<?php

namespace App\Listeners;

use App\Events\OperationAdminUpdate;

class SendOperationAdminUpdate
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
     * @param  \App\Events\OperationAdminUpdate  $event
     * @return void
     */
    public function handle(OperationAdminUpdate $event)
    {
        //
    }
}
