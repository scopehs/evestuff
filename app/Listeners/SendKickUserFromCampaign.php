<?php

namespace App\Listeners;

use App\Events\KickUserFromCampaign;

class SendKickUserFromCampaign
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
     * @param  KickUserFromCampaign  $event
     * @return void
     */
    public function handle(KickUserFromCampaign $event)
    {
        //
    }
}
