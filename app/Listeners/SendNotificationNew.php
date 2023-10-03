<?php

namespace App\Listeners;

use App\Events\NotificationNew;

class SendNotificationNew
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
     * @param  object  $event
     * @return void
     */
    public function handle(NotificationNew $event)
    {
        //
    }
}
