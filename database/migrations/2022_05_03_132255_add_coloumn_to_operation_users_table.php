<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumnToOperationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operation_users', function (Blueprint $table) {
            $table->boolean('primery')->default(0)->after('user_status_id');
            $table->foreignId('new_system_node_id')->after('user_status_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation_users', function (Blueprint $table) {
            $table->dropColumn('primery');
            $table->dropColumn('new_system_node_id');
        });
    }
}
