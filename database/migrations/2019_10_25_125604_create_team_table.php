<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_members', function (Blueprint $table)
        {
            $table->increments('team_member_id');
            $table->string('team_member_name');
            $table->string('team_member_role')->nullable();
            $table->longText('team_member_description');
            $table->string('team_member_photo')->nullable();
            $table->string('team_member_phone')->nullable();
            $table->string('team_member_email')->nullable();
            $table->string('team_member_linkedin')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_members');
    }
}
