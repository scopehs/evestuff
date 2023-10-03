<?php

namespace App\Listeners;

use App\Events\NodeAttackMessageUpdate;

class SendNodeAttackMessageUpdate
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
     * @param  NodeAttackMessageUpdate  $event
     * @return void
     */
    public function handle(NodeAttackMessageUpdate $event)
    {
        //
    }
}
