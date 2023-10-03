<?php

namespace App\Console\Commands;

use App\Jobs\AddMainEveIDToUsers;
use App\Models\EveEsiStatus;
use App\Models\User;
use Illuminate\Console\Command;

class AddEveIDToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eveiduser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the main eve user id to each user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $status = EveEsiStatus::where('route', '/universe/ids/')->first();
        if ($status->status == "green") {
            $users = User::where('eve_user_id', 0)->whereNotIn('id', [1, 999999999, 9999999999, 10000000000, 10000000002, 10000000004, 10000000007, 2285, 22795, 27235,])->get();
            foreach ($users as $user) {
                AddMainEveIDToUsers::dispatch($user->name);
            }
        }
    }
}
