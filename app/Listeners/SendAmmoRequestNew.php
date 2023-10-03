<?php

namespace App\Listeners;

use App\Events\AmmoRequestNew;

class SendAmmoRequestNew
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
     * @param  AmmoRequestNew  $event
     * @return void
     */
    public function handle(AmmoRequestNew $event)
    {
        //
    }
}
