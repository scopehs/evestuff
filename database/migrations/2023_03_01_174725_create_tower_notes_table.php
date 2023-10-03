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
        Schema::create('tower_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tower_id');
            $table->foreignId('user_id');
            $table->text('message');
            $table->json('read_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tower_notes');
    }
};
