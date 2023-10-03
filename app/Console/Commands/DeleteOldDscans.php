<?php

namespace App\Console\Commands;

use App\Events\dScanSoloUpdate;
use App\Models\Dscan;
use App\Models\DscanHistory;
use App\Models\DscanItem;
use App\Models\DscanLocal;
use Illuminate\Console\Command;

class DeleteOldDscans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:removeOldDscans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will remove all dscans that have not been updated in 6hours along with all related stuff.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $dscans = Dscan::where('updated_at', '<', now()->subHours(6))
            ->whereType(0)
            ->get();
        foreach ($dscans as $dscan) {
            DscanHistory::where('dscan_id', $dscan->id)->delete();
            DscanItem::where('dscan_id', $dscan->id)->delete();
            DscanLocal::where('dscan_id', $dscan->id)->delete();


            $link = $dscan->link;
            $flag = collect([
                'id' => $link,
                'flag' => 11,
            ]);
            broadcast(new dScanSoloUpdate($flag))->toOthers();
            $dscan->delete();
        }
    }
}
