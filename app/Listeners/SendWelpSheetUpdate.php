<?php

namespace App\Listeners;

use App\Events\WelpSheetUpdate;

class SendWelpSheetUpdate
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
     * @param  \App\Events\WelpSheetUpdate  $event
     * @return void
     */
    public function handle(WelpSheetUpdate $event)
    {
        //
    }
}
