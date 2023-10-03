<?php

namespace App\Jobs;

use App\Events\StationSheetUpdateWebway;
use App\Models\WebWay;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class getWebwayJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $system_id;

    protected $link;

    protected $jumps;

    protected $link_p;

    protected $jumps_p;

    protected $start_system_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start_system_id, $system_id, $link, $jumps, $link_p, $jumps_p)
    {
        $this->start_system_id = $start_system_id;
        $this->system_id = $system_id;
        $this->link = $link;
        $this->link_p = $link_p;
        $this->jumps = $jumps;
        $this->jumps_p = $jumps_p;
    }

    /**
     * Execute the job.dwadawdwadwadwa
     *
     * @return void
     */
    public function handle()
    {
        activity()->disableLogging();
        $send = false;

        $old = WebWay::where([
            'start_system_id' => $this->start_system_id,
            'system_id' => $this->system_id,
            'permissions' => 0,
        ])->first();

        $oldp = WebWay::where([
            'start_system_id' => $this->start_system_id,
            'system_id' => $this->system_id,
            'permissions' => 1,
        ])->first();

        WebWay::updateOrCreate(
            [
                'start_system_id' => $this->start_system_id,
                'system_id' => $this->system_id,
                'permissions' => 0,
            ],
            [
                'webway' => $this->link,
                'jumps' => $this->jumps,
            ]
        );

        WebWay::updateOrCreate(
            [
                'start_system_id' => $this->start_system_id,
                'system_id' => $this->system_id,
                'permissions' => 1,
            ],
            [
                'webway' => $this->link_p,
                'jumps' => $this->jumps_p,
            ]
        );

        if ($old) {
            if ($old->webway != $this->link || $old->jumps != $this->jumps) {
                $send = true;
            }
        } else {
            $send = true;
        }

        if ($oldp) {
            if ($oldp->webway != $this->link_p || $oldp->jumps != $this->jumps_p) {
                $send = true;
            }
        } else {
            $send = true;
        }

        if ($send) {
            $system = $this->system_id;
            $flag = collect([
                'id' => $system,
            ]);

            broadcast(new StationSheetUpdateWebway($flag));
        }
        activity()->enableLogging();
    }
}
