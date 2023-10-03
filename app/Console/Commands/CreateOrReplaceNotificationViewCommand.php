<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceNotificationViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceNotificationRecordsView';

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
        DB::statement("CREATE OR REPLACE VIEW notification_records AS SELECT notifications.id AS 'id',
        notifications.system_id AS 'system_id',
        regions.region_name AS 'region_name',
        regions.id AS 'region_id',
        constellations.constellation_name AS 'constellation_name',
        systems.system_name AS 'system_name',
        notifications.item_id AS 'item_id',
        items.item_name AS 'item_name',
        systems.adm AS 'adm',
        notifications.notification_type_id AS 'notification_type_id',
        notification_types.name AS 'notification_type_name',
        notifications.status_id AS 'status_id',
        statuses.name AS 'status_name',
        notifications.timestamp AS 'timestamp',
        notifications.text as 'text',
        users.name AS 'user_name',
        notifications.user_id AS 'user_id',
        notifications.end_time AS 'end_time'
        FROM notifications
        JOIN systems ON systems.id = notifications.system_id
        JOIN constellations ON constellations.id = systems.constellation_id
        JOIN regions ON regions.id = systems.region_id
        JOIN items ON items.id = notifications.item_id
        JOIN statuses ON statuses.id = notifications.status_id
        JOIN notification_types ON notification_types.id = notifications.notification_type_id
        LEFT JOIN users ON users.id = notifications.user_id");
    }
}
