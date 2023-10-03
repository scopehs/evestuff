<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Models\Dscan;
use App\Models\DscanHistory;
use App\Models\DscanItem;
use App\Models\DscanLocal;
use Illuminate\Console\Command;

class ClearAddDscanInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:ClearAllDscanInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Clearing Dscan Info');
        DscanItem::truncate();
        DscanHistory::truncate();
        DscanLocal::truncate();
        Dscan::truncate();
        Character::whereNull('corp_id')->delete();
        $this->info('Dscan Info Cleared');
    }
}
