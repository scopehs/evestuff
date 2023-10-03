<?php

use App\Models\CampaignUser;
use App\Models\OperationUser;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = CampaignUser::all();
        OperationUser::truncate();
        foreach ($users as $user) {
            $new = new OperationUser();
            $new->name = $user->char_name;
            $new->ship = $user->ship;
            $new->entosis = $user->link ?? 0;
            $new->user_id = $user->site_id;
            $new->role_id = $user->campaign_role_id;
            $new->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
