<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFooterLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('footer_links', function (Blueprint $table)
        {
            $table->enum('footer_link_type', ['existing-url','custom-link',])->after('footer_link_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('footer_links', function (Blueprint $table)
        {
            $table->dropColumn('footer_link_type');
        });
    }
}
