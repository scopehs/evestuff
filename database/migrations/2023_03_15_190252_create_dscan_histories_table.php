<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dscan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dscan_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('system_id')->nullable();
            $table->uuid('link')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->json('totals')->nullable();
            $table->json('dscan')->nullable();
            $table->json('corpsTotal')->nullable();
            $table->json('alliancesTotal')->nullable();
            $table->integer('history_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dscan_histories');
    }
};
