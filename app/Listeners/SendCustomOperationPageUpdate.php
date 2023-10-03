<?php

namespace App\Listeners;

use App\Events\CustomOperationPageUpdate;

class SendCustomOperationPageUpdate
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
     * @param  \App\Events\CustomOperationPageUpdate  $event
     * @return void
     */
    public function handle(CustomOperationPageUpdate $event)
    {
        //
    }
}
