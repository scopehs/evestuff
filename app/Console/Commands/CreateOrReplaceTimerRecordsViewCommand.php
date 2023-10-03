<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceTimerRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceTimerRecordsView';

    /**CreateOrReaplceTimerRecordsView
     * CreateOrReplaceTimerRecordsView
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or Replace SQL View';

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
        DB::statement("CREATE OR REPLACE VIEW timer_records AS SELECT regions.region_name AS 'region',
        regions.id AS 'region_id',
        constellations.constellation_name AS 'constellation',
        constellations.id AS 'constellation_id',
        systems.system_name AS 'system',
        systems.id AS 'system_id',
        alliances.name AS 'alliance',
        alliances.id AS 'alliance_id',
        alliances.standing AS 'standing',
        alliances.ticker AS 'ticker',
        alliances.url AS 'url',
        alliances.color AS 'color',
        items.item_name AS 'type',
        structures.adm AS 'adm',
        structures.age AS 'age',
        structures.vulnerable_start_time AS 'start',
        structures.vulnerable_end_time as 'end',
        structures.status as 'status',
        structures.id as 'id',
        if(structures.vulnerable_start_time < UTC_TIMESTAMP AND structures.vulnerable_end_time > UTC_DATE IS NOT NULL,'Open','Closed') as 'window_station',
        if(structures.vulnerable_start_time < UTC_TIMESTAMP AND structures.vulnerable_end_time > UTC_DATE IS NOT NULL, structures.vulnerable_end_time,structures.vulnerable_start_time) as 'time'
        FROM structures
        JOIN alliances ON alliances.id = structures.alliance_id
        JOIN systems ON systems.id = structures.system_id
        JOIN regions ON regions.id = systems.region_id
        JOIN items ON items.id = structures.item_id
        JOIN constellations ON constellations.id = systems.constellation_id
        WHERE structures.vulnerable_start_time IS NOT NULL");
    }
}
