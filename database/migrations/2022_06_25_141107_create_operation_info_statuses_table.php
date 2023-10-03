<?php

use App\Models\OperationInfoStatus;
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
        Schema::create('operation_info_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        OperationInfoStatus::create(['id' => 1, 'name' => 'planning']);
        OperationInfoStatus::create(['id' => 2, 'name' => 'forming']);
        OperationInfoStatus::create(['id' => 3, 'name' => 'active']);
        OperationInfoStatus::create(['id' => 4, 'name' => 'post']);
        OperationInfoStatus::create(['id' => 5, 'name' => 'done']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_info_statuses');
    }
};
