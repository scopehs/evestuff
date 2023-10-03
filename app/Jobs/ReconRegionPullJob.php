<?php

namespace App\Jobs;

use App\Models\Station;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReconRegionPullJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $stationID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stationID)
    {
        $this->stationID = $stationID;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        activity()->disableLogging();
        if ($this->stationID == 0) {
            return;
        }
        $current_time = now();
        $x_minutes_ago = $current_time->subMinutes(5);
        $station = Station::whereId($this->stationID)->first();
        if ($station) {
            if ($station->updated_at <= $x_minutes_ago) {
                reconRegionPullIdCheck($this->stationID);
                // sendStationListUpdateToWatchListPage($this->stationID);
            }
        } else {
            reconRegionPullIdCheck($this->stationID);
            sendStationListUpdateToWatchListPage($this->stationID);
        }
        activity()->enableLogging();
    }
}
