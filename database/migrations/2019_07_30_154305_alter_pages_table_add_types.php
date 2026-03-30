<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPagesTableAddTypes extends Migration
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
           $table->string('header_title')->nullable()->after('title');
           $table->enum('page_type', ['bespoke', 'page'])->default('page')->nullable()->after('content');
           $table->enum('video_type', ['youtube', 'vimeo'])->nullable()->after('page_type');
           $table->string('video_id')->after('video_type')->nullable();
           $table->softDeletes()->after('photo');
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
           $table->dropColumn('page_type');
           $table->dropColumn('video_type');
           $table->dropColumn('video_id');

        });
    }
}
