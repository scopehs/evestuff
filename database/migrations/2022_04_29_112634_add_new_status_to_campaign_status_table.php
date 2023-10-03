<?php

use App\Models\CampaignStatus;
use Illuminate\Database\Migrations\Migration;

class AddNewStatusToCampaignStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CampaignStatus::create(['id' => 5, 'name' => 'warmup']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CampaignStatus::where('id', 5)->delete();
    }
}
