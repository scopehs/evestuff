<?php

namespace App\Listeners;

use App\Events\NodeJoinDelete;

class SendNodeJoinDelete
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
     * @param  NodeJoinDelete  $event
     * @return void
     */
    public function handle(NodeJoinDelete $event)
    {
        //
    }
}
