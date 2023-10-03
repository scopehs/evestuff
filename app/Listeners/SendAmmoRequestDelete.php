<?php

namespace App\Listeners;

use App\Events\AmmoRequestDelete;

class SendAmmoRequestDelete
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
     * @param  AmmoRequestDelete  $event
     * @return void
     */
    public function handle(AmmoRequestDelete $event)
    {
        //
    }
}
