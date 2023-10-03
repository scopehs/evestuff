<?php

namespace App\Listeners;

use App\Events\CampaignSystemDelete;

class SendCampaignSystemDelete
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
     * @param  CampaignSystemDelete  $event
     * @return void
     */
    public function handle(CampaignSystemDelete $event)
    {
        //
    }
}
