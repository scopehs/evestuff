<?php

namespace App\Console\Commands;

use App\Jobs\updateWebwayJob;
use App\Models\Campaign;
use App\Models\NewCampaignSystem;
use App\Models\StagingSystem;
use App\Models\Station;
use App\Models\Userlogging;
use App\Models\WebWay;
use App\Models\WebWayStartSystem;
use Illuminate\Console\Command;

class UpdateWebWayRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:webway';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will get all the route links and jump counts for active systems';

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
        activity()->disableLogging();
        Userlogging::create(['url' => 'demon webway', 'user_id' => 9999999999]);
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);

        $start_system_id = env('HOME_SYSTEM_ID', ($variables && array_key_exists('HOME_SYSTEM_ID', $variables)) ? $variables['HOME_SYSTEM_ID'] : null);

        $w = WebWay::where('id', '>', 0)->get();
        foreach ($w as $w) {
            $w->update(['active' => 0]);
        }
        $stations = Station::get();
        $stationSystems = $stations->pluck('system_id');
        $campaigns = Campaign::get();
        $campaginSystems = $campaigns->pluck('system_id');
        $newCampaigns = NewCampaignSystem::get();
        $newCampaignsSystems = $newCampaigns->pluck('system_id');
        $staging = StagingSystem::get();
        $stagingSystems = $staging->pluck('system_id');

        $systemIDs = $stationSystems->merge($campaginSystems);
        $systemIDs = $systemIDs->merge($newCampaignsSystems);
        $systemIDs = $systemIDs->merge($stagingSystems);
        $systemIDs = $systemIDs->unique();
        $systemIDs = $systemIDs->values();
        $w = WebWay::whereIn('system_id', $systemIDs)->get();
        foreach ($w as $w) {
            $w->update(['active' => 1]);
        }
        $w = WebWay::where('active', 0)->get();
        foreach ($w as $w) {
            $w->delete();
        }

        foreach ($systemIDs as $end_system_id) {
            updateWebwayJob::dispatch($start_system_id, $end_system_id)->onQueue('webway');
        }

        $start_system_ids = WebWayStartSystem::where('system_id', '!=', 30004759)->pluck('system_id');
        foreach ($start_system_ids as $start_system_id) {
            foreach ($systemIDs as $end_system_id) {
                updateWebwayJob::dispatch($start_system_id, $end_system_id)->onQueue('webway');
            }
        }
        activity()->enableLogging();
    }
}
