<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fullname', 80);
            $table->string('email', 80);
            $table->string('contact', 80)->nullable();
            $table->unsignedTinyInteger('is_rental');
            $table->text('in')->nullable();
            $table->text('property_type_id')->nullable();
            $table->unsignedInteger('beds')->nullable();
            $table->unsignedInteger('price_from')->nullable();
            $table->unsignedInteger('price_to')->nullable();
            $table->tinyInteger('is_active')->nullable();
            //$table->string('token', 80)->nullable();
            //$table->mediumText('sent_properties')->nullable();
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
        Schema::dropIfExists('property_alerts');
    }
}
