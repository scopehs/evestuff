<?php

use App\Models\OperationInfoMumble;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        OperationInfoMumble::truncate();
        OperationInfoMumble::create(['name' => 'OP 1']);
        OperationInfoMumble::create(['name' => 'OP 2']);
        OperationInfoMumble::create(['name' => 'OP 3']);
        OperationInfoMumble::create(['name' => 'OP 4']);
        OperationInfoMumble::create(['name' => 'OP 5']);
        OperationInfoMumble::create(['name' => 'OP 6']);
        OperationInfoMumble::create(['name' => 'OP 7']);
        OperationInfoMumble::create(['name' => 'OP 8']);
        OperationInfoMumble::create(['name' => 'OP 9']);
        OperationInfoMumble::create(['name' => 'OP 10']);
        OperationInfoMumble::create(['name' => 'OP 11']);
        OperationInfoMumble::create(['name' => 'OP 12']);
        OperationInfoMumble::create(['name' => 'INIT OP 1']);
        OperationInfoMumble::create(['name' => 'INIT OP 2']);
        OperationInfoMumble::create(['name' => 'Home Defense']);
        OperationInfoMumble::create(['name' => 'ASCEE GateCamp']);
        OperationInfoMumble::create(['name' => 'Jita Riots']);
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
