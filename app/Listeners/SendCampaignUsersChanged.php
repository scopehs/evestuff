<?php

namespace App\Listeners;

use App\Events\CampaignUsersChanged;

class SendCampaignUsersChanged
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
    public function handle(CampaignUsersChanged $event)
    {
        //
    }
}
