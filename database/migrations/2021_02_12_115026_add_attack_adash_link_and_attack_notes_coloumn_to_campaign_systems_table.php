<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttackAdashLinkAndAttackNotesColoumnToCampaignSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_systems', function (Blueprint $table) {
            $table->text('attack_notes')->nullable()->after('notes');
            $table->text('attack_adash_link')->nullable()->after('attack_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_systems', function (Blueprint $table) {
            //
        });
    }
}
