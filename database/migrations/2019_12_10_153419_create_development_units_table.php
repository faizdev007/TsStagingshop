<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelopmentUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('development_units', function (Blueprint $table) {
            $table->increments('development_unit_id');
            $table->string('development_unit_name');
            $table->integer('property_type_id');
            $table->integer('development_id');
            $table->integer('development_unit_bedrooms');
            $table->integer('development_unit_bathrooms');
            $table->string('development_unit_status')->nullable();
            $table->string('development_unit_availability')->nullable();
            $table->integer('development_unit_price');
            $table->integer('development_unit_outside_area')->nullable();
            $table->integer('development_unit_inside_area')->nullable();
            $table->integer('order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('properties', function (Blueprint $table)
        {
            $table->string('development_id')->nullable()->after('agent_notes');
            $table->enum('is_development', ['y', 'n'])->default('n');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('development_units');

        Schema::table('properties', function (Blueprint $table)
        {
            $table->dropColumn('development_id');
            $table->dropColumn('is_development');
        });
    }
}
