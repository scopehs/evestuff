<?php

namespace App\Listeners;

use App\Events\KeyMessageUpdate;

class SendKeyMessageUpdate
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
     * @param  KeyMessageUpdate  $event
     * @return void
     */
    public function handle(KeyMessageUpdate $event)
    {
        //
    }
}
