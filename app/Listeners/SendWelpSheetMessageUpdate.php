<?php

namespace App\Listeners;

use App\Events\WelpSheetMessageUpdate;

class SendWelpSheetMessageUpdate
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
     * @param  \App\Events\WelpSheetMessageUpdate  $event
     * @return void
     */
    public function handle(WelpSheetMessageUpdate $event)
    {
        //
    }
}
