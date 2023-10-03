<?php

use App\Models\NewCampaign;
use App\Models\System;
use Illuminate\Database\Migrations\Migration;

class AddNamesToNewCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $campaings = NewCampaign::all();

        foreach ($campaings as $c) {
            if ($c->event_type == 32458) {
                $type = 'Ihub';
            } else {
                $type = 'TCU';
            }

            $system = System::where('id', $c->system_id)->first();
            $text = $system->system_name.' - '.$type;
            $c->update(['name' => $text]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
