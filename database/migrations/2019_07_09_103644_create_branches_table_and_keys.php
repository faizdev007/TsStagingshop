<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTableAndKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->increments('branch_id');
            $table->string('branch_name');
            $table->string('branch_address1');
            $table->string('branch_address2');
            $table->string('branch_town');
            $table->string('branch_postcode');
            $table->string('branch_phone');
            $table->string('branch_email');
            $table->softDeletes();
            $table->timestamps();
        });

        // Add Branch ID to User Table...
        Schema::table('users', function (Blueprint $table)
        {
            $table->string('branch_id')->nullable()->after('telephone');
        });

        // Add Branch ID to Property Table...
        Schema::table('properties', function (Blueprint $table)
        {
            $table->string('branch_id')->nullable()->after('ref');
        });

        // Add Branch ID to Leads Table...
        Schema::table('leads', function (Blueprint $table)
        {
            $table->string('branch_id')->nullable()->after('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
