<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSectionToPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table)
        {
            $table->text('content1')->nullable()->after('content');
            $table->string('heading1')->nullable()->after('content1');
            $table->string('heading2')->nullable()->after('heading1');
            $table->text('content2')->nullable()->after('heading2');
            $table->text('content3')->nullable()->after('content2');
            $table->text('content4')->nullable()->after('content3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table)
        {
           $table->dropColumn(['content1','heading1','heading2','content2','content3','content4']);

        });
    }
}
