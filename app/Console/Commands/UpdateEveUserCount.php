<?php

namespace App\Console\Commands;

use App\Events\EveUserUpdate;
use App\Models\Eve;
use App\Models\EveEsiStatus;
use App\Models\Userlogging;
use Illuminate\Console\Command;

class UpdateEveUserCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eveusercount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the eve usercount';

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
        Userlogging::create(['url' => 'demon eveCount', 'user_id' => 9999999999]);
        $check = EveEsiStatus::where('route', '/status/')->first();
        if ($check->status == 'green') {
            $count = eveUserCount();
        }
        Eve::where('id', 1)->update(['user_count' => $count]);

        $flag = collect([
            'message' => $count,
        ]);
        broadcast(new EveUserUpdate($flag));
    }
}
