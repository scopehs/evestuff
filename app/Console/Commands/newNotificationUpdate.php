<?php

namespace App\Console\Commands;

use App\Events\NotificationNew;
use App\Models\Auth;
use App\Models\EveEsiStatus;
use App\Models\Userlogging;
use Illuminate\Console\Command;

class newNotificationUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:newnotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will run and check for new notifications';

    /**
     * Create a new commadwdwadawdwand instance.cscscs
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
        Userlogging::create(['url' => 'demon notes', 'user_id' => 9999999999]);
        // $check = EveEsiStatus::where('route', '/characters/{character_id}/notifications/')->first();push baby push
        $check = true;
        // if ($check->status = 'green') {
        if ($check) {
            $char = Auth::where('flag_note', 0)->first();
            if ($char) {
                $charID = $char->char_id;
                $char->flag_note = 1;
                $char->save();
            } else {
                Auth::where('flag_note', 1)->update(['flag_note' => 0]);
                $char = Auth::where('flag_note', 0)->first();
                $charID = $char->char_id;
                $char->flag_note = 1;
                $char->save();
            }
            $refreshToken = refreshToken($charID);
            if ($refreshToken) {
                $data = getNotifications($charID);
                $flag = notificationUpdate($data);
                dd($flag);
                if ($flag['notificationflag'] == 1) {
                    broadcast(new NotificationNew($flag['notificationflag']))->toOthers();
                }
            }
        }
    }
}
