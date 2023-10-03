<?php

use App\Models\OperationInfoReconStatus;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        OperationInfoReconStatus::create(['id' => 4, 'name' => 'In Fleet and System']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        OperationInfoReconStatus::where('id', 4)->delete();
    }
};
