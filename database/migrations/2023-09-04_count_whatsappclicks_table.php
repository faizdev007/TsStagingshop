<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatewhatsappclicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('whatsapp_clicks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref', 50)->nullable();
            $table->string('p_name', 80)->nullable();
            $table->timestamp('clicked_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_clicks');
    }
}
