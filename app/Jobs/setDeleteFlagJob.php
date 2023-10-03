<?php

namespace App\Jobs;

use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\OperationUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class setDeleteFlagJob implements ShouldQueue
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
        $campaign = NewCampaign::where('id', $this->campaign_id)->with('operations')->first();
        $operations = $campaign->operations;
        foreach ($operations as $operation) {
            $count = $operation->campaign->count();
            if ($count == 1) {
                $this->deleteOperation($operation);
            }
        }
        NewCampaignOperation::where('campaign_id', $this->campaign_id)->delete();
        activity()->enableLogging();
    }

    public function deleteOperation($operation)
    {
        $operationUsers = OperationUser::where('operation_id', $operation->id)->get();
        foreach ($operationUsers as $operationUser) {
            $operationUser->operation_id = null;
            $operationUser->save();
        }
        $operation->delete();
    }
}
