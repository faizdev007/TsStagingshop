<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translations', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('text_line1')->nullable();
            $table->string('text_line2')->nullable();
            $table->string('quote')->nullable();
            $table->string('language');
            $table->longText('content')->nullable();
            $table->longText('description')->nullable();
            $table->integer('translationable_id');
            $table->string('translationable_type');
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
        Schema::dropIfExists('translations');
    }
}
