<?php

namespace App\Console\Commands;

use App\Models\Userlogging;
use Illuminate\Console\Command;

class UpdateStationNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:stationnotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will check station notification status';

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
        Userlogging::create(['url' => 'demon station note', 'user_id' => 9999999999]);
        stationNotificationCheck();
    }
}
