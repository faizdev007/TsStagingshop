<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 100)->nullable();
            $table->unsignedInteger('property_id');
            $table->string('p_name', 80)->nullable();
            $table->text('data')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_stats');
    }
}
