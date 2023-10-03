<?php

namespace App\Listeners;

use App\Events\CampaignUserNew;

class SendCampaignUserNew
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
     * @param  CampaignUserNew  $event
     * @return void
     */
    public function handle(CampaignUserNew $event)
    {
        //
    }
}
