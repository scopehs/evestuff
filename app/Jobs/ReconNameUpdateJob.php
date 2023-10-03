<?php

namespace App\Jobs;

use App\Models\Station;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReconNameUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stationId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stationId)
    {
        $this->stationId = $stationId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        activity()->disableLogging();

        if ($this->stationId == 0) {
            return;
        }
        reconNameUpdate($this->stationId);
        activity()->enableLogging();
    }
}
