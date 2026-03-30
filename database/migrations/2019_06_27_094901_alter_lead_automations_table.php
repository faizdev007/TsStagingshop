<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLeadAutomationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_automations', function (Blueprint $table)
        {
            $table->dropColumn('lead_type');
        });

        Schema::table('lead_automations', function (Blueprint $table)
        {
            $table->enum('lead_type', ['property', 'valuation', 'shortlist', 'search', 'property-alert', 'other']);
            $table->integer('user_id')->nullable();
        });
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
}
