<?php

namespace App\Jobs;

use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class setWarmUpdateFlagJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $campaign_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign_id)
    {
        $this->campaign_id = $campaign_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        activity()->disableLogging();
        $campaign = NewCampaign::where('id', $this->campaign_id)->first();
        $campaign->update(['status_id' => 5]);
        $operationIDs = NewCampaignOperation::where('campaign_id', $this->campaign_id)->get();
        foreach ($operationIDs as $opID) {
            broadcastOperationRefresh($opID->operation_id, $this->campaign_id, 8);
            broadcastSoloOpSoloOp(1, $opID->operation_id);
        }
        activity()->enableLogging();
    }

    public function uniqueId()
    {
        return $this->campaign_id;
    }
}
