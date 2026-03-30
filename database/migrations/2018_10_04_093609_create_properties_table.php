<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('feed_id')->nullable();
            $table->unsignedInteger('property_type_id')->nullable();
            $table->string('ref', 50)->unique()->nullable();
            $table->unsignedTinyInteger('is_rental');
            $table->unsignedTinyInteger('is_featured')->nullable();
            $table->string('street', 80)->nullable();
            $table->string('town', 80)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('complex_name', 80)->nullable();
            $table->string('region', 80)->nullable();
            $table->string('postcode', 8)->nullable();
            $table->string('country', 80);
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->string('price_qualifier', 20)->nullable();
            $table->unsignedInteger('beds')->nullable();
            $table->float('baths')->nullable();
            $table->float('area')->nullable();
            $table->string('name', 80)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('property_status', 40)->nullable();

            $table->unsignedInteger('internal_area')->nullable();
            $table->string('internal_area_unit', 40)->nullable(); //optional
            $table->unsignedInteger('rent_period')->nullable();

            $table->unsignedInteger('terrace_area')->nullable();
            $table->string('terrace_area_unit', 40)->nullable();

            $table->unsignedInteger('land_area')->nullable();
            $table->string('land_area_unit', 40)->nullable(); //optional

            $table->string('youtube_id', 40)->nullable();
            $table->text('add_info')->nullable();
            $table->text('description')->nullable();
            $table->text('agent_notes')->nullable();

            $table->string('property_type_name', 80)->nullable();

            $table->text('add_amenities')->nullable();
            $table->timestamp('archived_at')->nullable(); // Must add nullable
            $table->timestamps();
        });

        $statement = "ALTER TABLE properties AUTO_INCREMENT = 100;"; // Auto Incriment starts at 100
        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
