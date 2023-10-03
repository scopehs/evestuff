<?php

namespace App\Console\Commands;

use App\Models\Userlogging;
use Illuminate\Console\Command;

class UpdateAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this will update structure vul windows';

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
        Userlogging::create(['url' => 'demon auth', 'user_id' => 9999999999]);
        authcheck();
    }
}
