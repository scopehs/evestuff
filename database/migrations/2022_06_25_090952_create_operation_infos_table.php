<?php

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
        Schema::create('operation_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('start')->nullable();
            $table->boolean('planing_op_posted')->default(0);
            $table->boolean('planing_op_pre_ping')->default(0);
            $table->boolean('planing_op_recon_alerted')->default(0);
            $table->boolean('planing_op_capital_fc_found')->default(0);
            $table->boolean('planing_op_fc_found')->default(0);
            $table->boolean('planing_op_doctromes_decoded')->default(0);
            $table->boolean('planing_op_allies_infored')->default(0);
            $table->boolean('form_op_fc_ready')->default(0);
            $table->boolean('form_op_capital_fc_ready')->default(0);
            $table->boolean('form_op_recon_ready')->default(0);
            $table->boolean('form_op_scouts_ready')->default(0);
            $table->boolean('form_op_gsol_ready')->default(0);
            $table->boolean('form_op_blackhand_ready')->default(0);
            $table->boolean('form_op_gsfoe_ready')->default(0);
            $table->boolean('form_op_allies_ready')->default(0);
            $table->boolean('post_op_defrief_done')->default(0);
            $table->boolean('post_op_scouts_done')->default(0);
            $table->boolean('post_op_recon_done')->default(0);
            $table->boolean('post_op_fc_done')->default(0);
            $table->boolean('post_op_coord_done')->default(0);
            $table->foreignId('primary_coord_id')->nullable();
            $table->foreignId('primary_recon_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_infos');
    }
};
