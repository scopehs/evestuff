<?php

namespace App\Console\Commands;

use App\Jobs\setActiveUpdateFlagJob;
use App\Jobs\setWarmUpdateFlagJob;
use App\Models\EveEsiStatus;
use App\Models\NewCampaign;
use Carbon\Carbon;
use Illuminate\Console\Command;

class newCampaignUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:OLDnewCampaignsOLD';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New command to update sov stuff';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Userlogging::create(['url' => 'demon newCampaign', 'user_id' => 9999999999]);
        // $status = checkeve();
        // if ($status == 1) {
        //     NewnewUpdate();
        // };
        activity()->disableLogging();
        $check = EveEsiStatus::where('route', '/sovereignty/campaigns/')->first();
        if ($check) {
            if ($check->status == 'green') {
                newUpdateCampaigns();
            }
        }
        $campaigns = NewCampaign::where('job', 0)->get();
        foreach ($campaigns as $campaign) {
            $start = Carbon::parse($campaign->start_time);
            $twoHours = now()->addHours(3);

            if ($start <= $twoHours) {
                $a = $start->subHours(2);
                $begin = Carbon::parse($campaign->start_time);
                setWarmUpdateFlagJob::dispatch($campaign->id)->onQueue('campaigns')->delay($a);
                setActiveUpdateFlagJob::dispatch($campaign->id)->onQueue('campaigns')->delay($begin);
                $campaign->update(['job' => 1]);
            }
        }

        activity()->enableLogging();
    }
}
