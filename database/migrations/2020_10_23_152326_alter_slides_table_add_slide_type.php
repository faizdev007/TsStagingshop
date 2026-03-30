<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSlidesTableAddSlideType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slides', function (Blueprint $table)
        {
            $table->enum('type', ['image', 'video'])->default('image')->after('priority');
            $table->string('source')->nullable()->after('type');
            $table->string('video_id')->nullable()->after('source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slides', function (Blueprint $table)
        {
            $table->dropColumn('type');
            $table->dropColumn('source');
            $table->dropColumn('video_id');
        });
    }
}
