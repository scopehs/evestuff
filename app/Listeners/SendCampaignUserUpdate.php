<?php

namespace App\Listeners;

use App\Events\CampaignUserUpdate;

class SendCampaignUserUpdate
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
     * @param  CampaignUserUpdate  $event
     * @return void
     */
    public function handle(CampaignUserUpdate $event)
    {
        //
    }
}
