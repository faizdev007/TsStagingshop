<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLeadsAddUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table)
        {
            $table->string('user_id')->nullable()->after('branch_id');
            $table->uuid('uuid')->index()->after('ref');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table)
        {
            $table->dropColumn('user_id');
            $table->dropColumn('uuid');
        });
    }
}
