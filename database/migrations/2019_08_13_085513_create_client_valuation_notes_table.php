<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientValuationNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_valuation_notes', function (Blueprint $table) {
            $table->increments('client_valuation_note_id');
            $table->string('client_valuation_note_title');
            $table->text('client_valuation_text');
            $table->enum('client_valuation_note_type', ['internal', 'customer'])->default('internal');
            $table->string('client_valuation_id');
            $table->softDeletes();
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
        Schema::dropIfExists('client_valuation_notes');
    }
}
