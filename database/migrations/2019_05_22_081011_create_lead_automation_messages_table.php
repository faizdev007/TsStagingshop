<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadAutomationMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_automation_messages', function (Blueprint $table) {
            $table->increments('lead_automation_message_id');
            $table->integer('lead_id')->unsigned();
            $table->foreign('lead_id')
                ->references('id')
                ->on('leads');
            $table->integer('automation_id')->unsigned();
            $table->foreign('automation_id')
                ->references('lead_automation_id')
                ->on('lead_automations');
            $table->integer('message_id')->unsigned();
            $table->foreign('message_id')
                ->references('id')
                ->on('sent_emails');
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
        Schema::dropIfExists('lead_automation_messages');
    }
}
