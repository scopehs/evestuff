<?php

use App\Models\HotRegion;
use App\Models\Region;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class PopulateHotRegionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role "Coord" web "edit_hot_region"');
        Artisan::call('permission:create-role "Coord" web "Recon Leader"');
        $regionIDs = Region::whereNotNull('id')->pluck('id');
        foreach ($regionIDs as $id) {
            HotRegion::create(['region_id' => $id]);
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
}
