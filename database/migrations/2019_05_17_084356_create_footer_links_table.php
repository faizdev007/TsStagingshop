<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFooterLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footer_links', function (Blueprint $table) {
            $table->increments('footer_link_id');
            $table->string('footer_link_title');
            $table->mediumText('footer_link_url');
            $table->integer('footer_link_order')->default(0);
            $table->integer('footer_block_id')->unsigned();
            $table->foreign('footer_block_id')
                ->references('footer_block_id')
                ->on('footer_blocks');
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
        Schema::dropIfExists('footer_links');
    }
}
