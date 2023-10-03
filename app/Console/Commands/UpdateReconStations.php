<?php

namespace App\Console\Commands;

use App\Events\StationUpdateCoord;
use App\Jobs\ReconNameUpdateJob;
use App\Jobs\ReconRegionPullJob;
use App\Models\HotRegion;
use App\Models\Station;
use App\Models\Userlogging;
use Illuminate\Console\Command;

class UpdateReconStations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:reconstations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this will pull all station data from recon';

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
        Userlogging::create(['url' => 'demon recon station', 'user_id' => 9999999999]);
        dubp();
        $s = Station::get();
        foreach ($s as $s) {

            $s->update(['import_flag' => 0]);
        }

        Userlogging::create(['url' => 'demon region', 'user_id' => 9999999999]);
        $ids = HotRegion::where('update', 1)->pluck('region_id');

        foreach ($ids as $id) {
            $stations = reconRegionPull($id);
            foreach ($stations as $station) {
                $stationDB = Station::where('id', $station)->first();
                if ($stationDB) {
                    $stationDB->import_flag = 1;
                    $stationDB->save();
                }
                ReconRegionPullJob::dispatch($station);
            }
        }

        $missedStations = Station::where('import_flag', 0)->where('added_from_recon', 1)->get();
        foreach ($missedStations as $station) {
            $station->update(['import_flag' => 1]);
            $stationID = $station->id;
            ReconRegionPullJob::dispatch($stationID);
        }

        $stations = Station::where('import_flag', 0)->where('added_from_recon', 0)->get();
        foreach ($stations as $station) {
            $station->update(['import_flag' => 1]);
            $StationId = $station->id;
            ReconNameUpdateJob::dispatch($StationId);
        }

        // $flag = collect([
        //     'flag' => 1,
        // ]);
        // reconUpdate();
        // broadcast(new StationUpdateCoord($flag));
        activity()->enableLogging();
    }
}
