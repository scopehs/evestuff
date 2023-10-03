<?php

namespace App\Listeners;

use App\Events\NodeJoinUpdate;

class SendNodeJoinUpdate
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
     * @param  NodeJoinUpdate  $event
     * @return void
     */
    public function handle(NodeJoinUpdate $event)
    {
        //
    }
}
