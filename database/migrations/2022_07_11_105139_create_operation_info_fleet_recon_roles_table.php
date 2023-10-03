<?php

use App\Models\OperationInfoFleetReconRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_info_fleet_recon_roles', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->timestamps();
        });

        OperationInfoFleetReconRole::create(['id' => 1, 'name' => "Main"]);
        OperationInfoFleetReconRole::create(['id' => 2, 'name' => "Back Up"]);
        OperationInfoFleetReconRole::create(['id' => 3, 'name' => "Exit"]);
        OperationInfoFleetReconRole::create(['id' => 4, 'name' => "Mid-Point"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_info_fleet_recon_roles');
    }
};
