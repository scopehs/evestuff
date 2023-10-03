<?php

namespace App\Listeners;

use App\Events\EveUserUpdate;

class SendEveUserUpdate
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
     * @param  EveUserUpdate  $event
     * @return void
     */
    public function handle(EveUserUpdate $event)
    {
        //
    }
}
