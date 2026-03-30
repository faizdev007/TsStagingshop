<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyTypeIdsToPropertyAlerts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_alerts', function (Blueprint $table) {
            $table->string('property_type_ids')->nullable()->after('property_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_alerts', function (Blueprint $table) {
            $table->dropColumn('property_type_ids');
        });
    }
}
