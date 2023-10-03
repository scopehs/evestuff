<?php

namespace App\Listeners;

use App\Events\CampaignUserDelete;

class SendCampaignUserDelete
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
     * @param  CampaignUserDelete  $event
     * @return void
     */
    public function handle(CampaignUserDelete $event)
    {
        //
    }
}
