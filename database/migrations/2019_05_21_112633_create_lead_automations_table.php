<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadAutomationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_automations', function (Blueprint $table) {
            $table->increments('lead_automation_id');
            $table->integer('lead_id')->unsigned();
            $table->foreign('lead_id')
                ->references('id')
                ->on('leads');
            $table->enum('lead_type', ['property', 'valuation', 'other']);
            $table->enum('lead_is_subscribed', ['y', 'n'])->default('y');
            $table->enum('lead_contact_type', ['email', 'sms'])->default('email');
            $table->dateTime('last_contacted');
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
        Schema::dropIfExists('lead_automations');
    }
}
