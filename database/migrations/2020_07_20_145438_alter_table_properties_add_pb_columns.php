<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePropertiesAddPbColumns extends Migration
{
    /**
     * Run the migrations.
     **** OPTIONAL*** - THIS IS FOR ALL PROPERTYBASE SITES *****
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table)
        {
            $table->string('pb_id')->nullable()->after('ref');
            $table->longText('propertybase_fields')->nullable()->after('internal_area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table)
        {
            $table->dropColumn('pb_id');
            $table->dropColumn('propertybase_fields');
        });
    }
}
