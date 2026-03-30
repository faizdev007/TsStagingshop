<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //$table->dropColumn('name');
            //$table->string('first_name', 40)->nullable();
            //$table->string('last_name', 40)->nullable();
            $table->string('telephone', 40)->nullable();
            //$table->string('website', 200)->nullable();
            $table->string('role', 10);
            $table->tinyInteger('status');
            $table->string('path')->nullable();
        });

        $statement = "ALTER TABLE users AUTO_INCREMENT = 100;"; // Auto Incriment starts at 100
        DB::unprepared($statement);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //$table->string('name');
            //$table->dropColumn(['first_name', 'last_name', 'telephone', 'website', 'role']);
            //$table->dropColumn(['telephone','role']);
            $table->dropColumn(['telephone']);
        });
    }
}
