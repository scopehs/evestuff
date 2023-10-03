<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id()->index();
            $table->foreignId('system_id')->index();
            $table->foreignId('item_id')->index();
            $table->foreignId('corp_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('text')->nullable();
            $table->string('notes')->nullable();
            $table->string('attack_notes')->nullable();
            $table->string('attack_adash_link')->nullable();
            $table->integer('station_status_id')->default(1)->index();
            $table->foreignId('gunner_id')->nullable();
            $table->timestamp('out_time')->nullable();
            $table->timestamp('repair_time')->nullable();
            $table->foreignId('ammo_request_id')->nullable();
            $table->string('r_hash')->nullable();
            $table->dateTime('r_updated_at')->nullable();
            $table->string('r_fitted')->nullable();
            $table->integer('r_capital_shipyard')->default(0);
            $table->integer('r_hyasyoda')->default(0);
            $table->integer('r_invention')->default(0);
            $table->integer('r_manufacturing')->default(0);
            $table->integer('r_research')->default(0);
            $table->integer('r_supercapital_shipyard')->default(0);
            $table->integer('r_biochemical')->default(0);
            $table->integer('r_hybrid')->default(0);
            $table->integer('r_moon_drilling')->default(0);
            $table->integer('r_reprocessing')->default(0);
            $table->integer('r_point_defense')->default(0);
            $table->integer('r_dooms_day')->default(0);
            $table->integer('r_guide_bombs')->default(0);
            $table->integer('r_anti_cap')->default(0);
            $table->integer('r_anti_subcap')->default(0);
            $table->integer('r_t2_rigged')->default(0);
            $table->integer('r_cloning')->default(0);
            $table->integer('r_composite')->default(0);
            $table->timestamp('status_update')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->string('r_cored')->nullable();
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
        Schema::dropIfExists('stations');
    }
}
