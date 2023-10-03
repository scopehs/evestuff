<?php

namespace App\Listeners;

use App\Events\LoggingUpdate;

class SendLoggingUpdate
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
     * @param  LoggingUpdate  $event
     * @return void
     */
    public function handle(LoggingUpdate $event)
    {
        //
    }
}
