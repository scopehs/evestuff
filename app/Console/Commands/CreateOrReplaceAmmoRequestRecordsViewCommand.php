<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceAmmoRequestRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceAmmoRequestRecordsView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make the view table for ammo request';

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
        DB::statement(
            "CREATE OR REPLACE VIEW ammo_request_records AS SELECT ammo_requests.id AS 'id',
ammo_requests.station_id AS 'station_id',
stations.name AS 'station_name',
stations.system_id AS 'system_id',
systems.system_name AS 'system_name',
systems.constellation_id AS 'constellation_id',
constellations.constellation_name AS 'constellation_name',
constellations.region_id AS 'region_id',
regions.region_name AS 'region_name',
alliances.ticker AS 'alliance_ticker',
stations.item_id AS 'item_id',
items.item_name AS 'item_name',
ammo_requests.user_id AS 'user_id',
users.name AS 'user_name',
ammo_requests.current_ammo AS 'current_ammo',
ammo_requests.request_text AS 'request_text',
ammo_requests.created_at AS 'start_time',
alliances.url AS 'url'
FROM ammo_requests
JOIN stations ON ammo_requests.station_id = stations.id
JOIN systems ON stations.system_id = systems.id
JOIN constellations ON systems.constellation_id = constellations.id
JOIN regions ON constellations.region_id = regions.id
JOIN corps ON stations.corp_id = corps.id
JOIN alliances ON corps.alliance_id = alliances.id
JOIN items ON stations.item_id = items.id
LEFT JOIN users ON ammo_requests.user_id = users.id"
        );
    }
}
