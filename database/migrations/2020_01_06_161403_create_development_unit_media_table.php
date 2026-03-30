<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelopmentUnitMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('development_unit_media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unit_id');
            $table->integer('development_id')->nullable();
            $table->string('type');
            $table->string('title')->nullable();
            $table->string('path');
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('development_unit_media');
    }
}
