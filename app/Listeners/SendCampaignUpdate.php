<?php

namespace App\Listeners;

use App\Events\CampaignUpdate;

class SendCampaignUpdate
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
     * @param  CampaignUpdate  $event
     * @return void
     */
    public function handle(CampaignUpdate $event)
    {
        //
    }
}
