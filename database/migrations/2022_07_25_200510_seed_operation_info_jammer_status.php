<?php

use App\Models\OperationInfoJammedStatus;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        OperationInfoJammedStatus::where('id', 1)->update(['name' => 'Unknown']);
        OperationInfoJammedStatus::where('id', 2)->update(['name' => 'Not Jammed']);
        $new = new OperationInfoJammedStatus();
        $new->id = 3;
        $new->name = "Jammed";
        $new->save();
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
