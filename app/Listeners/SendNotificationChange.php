<?php

namespace App\Listeners;

use App\Events\NotificationChanged;

class SendNotificationChange
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
    public function handle(NotificationChanged $event)
    {
        //
    }
}
