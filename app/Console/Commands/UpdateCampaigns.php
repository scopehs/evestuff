<?php

namespace App\Console\Commands;

use App\Models\EveEsiStatus;
use App\Models\Userlogging;
use Illuminate\Console\Command;

class UpdateCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update all campaign data';

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
    // public function handle()
    // {
    //     $status = checkeve();
    //     if ($status == 1) {
    //         $flag = update();
    //         if ($flag == 1) {
    //             broadcast(new CampaignChanged($flag))->toOthers();
    //         }
    //     }
    // }

    // public function handle()
    // {
    //     $status = checkeve();
    //     if ($status == 1) {
    //         $request = update();
    //         $flag = $request[0];
    //         if ($flag == 1) {
    //             broadcast(new CampaignChanged($flag))->toOthers();
    //         }
    //         $flag = null;
    //         $check = $request[1];
    //         foreach ($check as $check) {
    //             removeNode($check);
    //             $flag = collect([
    //                 'flag' => 4,
    //                 'id' => $check
    //             ]);
    //             broadcast(new CampaignSystemUpdate($flag))->toOthers();
    //             // }
    //         }
    //     }

    // }

    public function handle()
    {

        // $status = checkeve();
        Userlogging::create(['url' => 'demon campagin', 'user_id' => 9999999999]);
        $check = EveEsiStatus::where('route', '/sovereignty/campaigns/')->first();
        if ($check->status == 'green') {
            campaignUpdate();
        }
    }
}
