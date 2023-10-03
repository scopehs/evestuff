<?php

namespace App\Console\Commands;

use App\Models\Userlogging;
use Illuminate\Console\Command;

class UpdateTowers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:towers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'will check status of tower and update as needed';

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
        Userlogging::create(['url' => 'demon towers', 'user_id' => 9999999999]);
        towerUpdate();
    }
}
