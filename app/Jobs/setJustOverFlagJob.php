<?php

namespace App\Jobs;

use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewSystemNode;
use App\Models\NewUserNode;
use App\Models\OperationUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class setJustOverFlagJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $campaign_id;

    protected $defender_score;

    /**
     * Create a new job instance.ssss
     *
     * @return void
     */
    public function __construct($campaign_id, $defender_score)
    {
        $this->campaign_id = $campaign_id;
        $this->defender_score = $defender_score;
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
        $tenMins = now()->addMinutes(10);
        $tommorw = now()->addDay();

        $campaign->job = 2;
        $campaign->save();

        if ($this->defender_score > 0.5) {
            $dScore = 1;
            $aScore = 0;
        } else {
            $dScore = 0;
            $aScore = 1;
        }

        $campaign->update([
            'end_time' => now(),
            'status_id' => 3,
            'check' => 1,
            'defenders_score' => $dScore,
            'attackers_score' => $aScore,
        ]);

        $campaign = NewCampaign::where('id', $this->campaign_id)->first();
        $campaign->update(['status_id' => 3]);
        $operationIDs = NewCampaignOperation::where('campaign_id', $this->campaign_id)->get();
        foreach ($operationIDs as $opID) {
            $this->cleanUpCampaign($this->campaign_id);
            broadcastOperationRefresh($opID->operation_id, $this->campaign_id, 8);
            broadcastSoloOpSoloOp(1, $opID->operation_id);
        }
        operationInfoCampaignsSoloBcast($this->campaign_id, 17);

        setOverFlagJob::dispatch($this->campaign_id)->onQueue('campaigns')->delay($tenMins);
        setDeleteFlagJob::dispatch($this->campaign_id)->onQueue('campaigns')->delay($tommorw);
        activity()->enableLogging();
    }

    public function cleanUpCampaign($campaignID)
    {
        $SystemNodes = NewSystemNode::where('campaign_id', $campaignID)->get();
        foreach ($SystemNodes as $systemNode) {
            $userNodes = NewUserNode::where('node_id', $systemNode->id)->get();
            foreach ($userNodes as $userNode) {
                $opUser = OperationUser::where('new_user_node_id', $userNode->id)->first();
                $opUserID = $opUser->id;
                $opID = $opUser->operation_id;
                $opUser->user_status_id = 3;
                $opUser->new_user_node_id = null;
                $opUser->save();
                $userNode->delete();
                broadcastuserSolo($opID, $opUserID, 6);
            }
            $systemNode->delete();
        }
        $campaignSystems = NewCampaignSystem::where('new_campaign_id', $campaignID)->get();
        foreach ($campaignSystems as $campaignSystem) {
            $campaignSystem->delete();
        }
    }
}
