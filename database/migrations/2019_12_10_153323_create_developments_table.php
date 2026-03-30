<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelopmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developments', function (Blueprint $table)
        {
            $table->increments('development_id');
            $table->string('development_title');
            $table->string('development_heading');
            $table->string('development_subheading');
            $table->date('completion_date');
            $table->string('development_developer');
            $table->string('development_status');
            $table->date('development_construction_start')->nullable();
            $table->integer('development_price_from')->nullable();
            $table->integer('development_price_to')->nullable();
            $table->integer('property_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('developments');
    }
}
