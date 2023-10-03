<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceTowerRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceTowerRecordsView';

    /**
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
        DB::statement("CREATE OR REPLACE VIEW tower_records AS SELECT towers.id AS 'id',
        towers.alliance_id AS 'alliance_id',
        alliances.name AS 'alliance_name',
        alliances.ticker AS 'alliance_ticker',
        towers.item_id AS 'item_id',
        items.item_name AS 'item_name',
        towers.moon_id AS 'moon_id',
        moons.name AS 'moon_name',
        systems.system_name AS 'system_name',
        constellations.constellation_name AS 'constellation_name',
        regions.region_name AS 'region_name',
        towers.tower_status_id AS 'tower_status_id',
        tower_statuses.name AS 'tower_status_name',
        towers.user_id AS 'user_id',
        users.name AS 'user_name',
        towers.text AS 'text',
        towers.notes AS 'notes',
        towers.timestamp AS 'timestamp',
        towers.out_time AS 'out_time',
        if(towers.out_time IS NOT NULL, towers.out_time, towers.timestamp) AS 'view_time',
        alliances.url AS 'url',
        alliances.standing AS 'standing'
        FROM towers
        JOIN moons ON moons.id = towers.moon_id
        JOIN alliances ON alliances.id = towers.alliance_id
        JOIN items ON items.id = towers.item_id
        JOIN systems ON systems.id = moons.system_id
        JOIN constellations ON constellations.id = moons.constellation_id
        JOIN regions ON regions.id = moons.region_id
        JOIN tower_statuses ON tower_statuses.id = towers.tower_status_id
        LEFT JOIN users ON users.id = towers.user_id
        WHERE towers.tower_status_id != 10");
    }
}
