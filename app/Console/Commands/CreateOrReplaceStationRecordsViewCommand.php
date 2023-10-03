<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceStationRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceStationRecordsView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make the view table for Stations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // items.item_name AS 'item_name',
    /**
     * Execute the console com ffmand.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement("CREATE OR REPLACE VIEW station_records AS SELECT stations.id AS 'id',
       stations.name AS 'station_name',
       stations.system_id AS 'system_id',
       systems.system_name AS 'system_name',
       constellations.constellation_name AS 'constellation_name',
       regions.region_name AS 'region_name',
       regions.id AS 'region_id',
       stations.item_id AS 'item_id',
       if(items.id = 35840 , 'Cyno Beacon', if(items.id = 37534 , 'Cyno Jammer', if(items.id = 35841, 'Jump Gate', items.item_name))) AS 'item_name',
       stations.user_id AS 'user_id',
       s.name AS 'user_name',
       stations.gunner_id AS 'gunner_id',
       g.name AS 'gunner_name',
       stations.station_status_id AS 'station_status_id',
       station_statuses.name AS 'station_status_name',
       if(stations.out_time IS NULL,stations.timestamp, stations.out_time) AS 'timestamp',
       stations.out_time AS 'out_time',
       corps.ticker AS 'corp_ticker',
       stations.corp_id AS 'corp_id',
       alliances.name AS 'alliance_name',
       alliances.ticker AS 'alliance_ticker',
       stations.text AS 'text',
       stations.notes AS 'notes',
       stations.r_fitted AS fitted,
       stations.added_by_user_id AS 'added_by_user_id',
       i.name AS 'added_name',
       stations.attack_notes AS 'attack_notes',
       stations.attack_adash_link AS 'attack_adash_link',
       if(((stations.attack_notes IS NULL) AND (stations.attack_adash_link IS NULL)),0,1) AS under_attack,
       stations.repair_time AS 'repair_time',
       if(stations.corp_id = 0 ,0,alliances.standing) AS 'standing',
       alliances.url AS 'url',
       stations.timer_image_link AS timer_image_link,
       stations.show_on_main AS 'show_on_main',
       stations.show_on_chill AS 'show_on_chill',
       stations.show_on_welp AS 'show_on_welp',
       stations.show_on_rc_move AS 'show_on_rc_move',
       stations.show_on_coord AS 'show_on_coord',
       stations.added_from_recon AS 'added_from_recon',
       if(stations.ammo_request_id IS NULL,0,1) AS 'ammo_request'
       FROM stations
       LEFT JOIN systems ON systems.id = stations.system_id
       LEFT JOIN corps ON corps.id = stations.corp_id
       LEFT JOIN alliances on alliances.id = corps.alliance_id
       LEFT JOIN constellations ON constellations.id = systems.constellation_id
       LEFT JOIN regions ON regions.id = systems.region_id
       LEFT JOIN items ON items.id = stations.item_id
       LEFT JOIN station_statuses ON station_statuses.id = stations.station_status_id
       LEFT JOIN users AS s ON s.id = stations.user_id
       LEFT JOIN users AS g ON g.id = stations.gunner_id
       LEFT JOIN users AS i on i.id = stations.added_by_user_id
       WHERE stations.station_status_id != 10");
    }
}
