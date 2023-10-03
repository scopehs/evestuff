<?php

namespace App\Listeners;

use App\Events\NodeMessageUpdate;

class SendNodeMessageUpdate
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
     * @param  NodeMessageUpdate  $event
     * @return void
     */
    public function handle(NodeMessageUpdate $event)
    {
        //
    }
}
