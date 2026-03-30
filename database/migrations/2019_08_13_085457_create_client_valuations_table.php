<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientValuationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_valuations', function (Blueprint $table) {
            $table->increments('client_valuation_id');
            $table->uuid('uuid')->index();
            $table->integer('client_id');
            $table->string('client_valuation_street');
            $table->string('client_valuation_town')->nullable();
            $table->string('client_valuation_city');
            $table->string('client_valuation_region')->nullable();
            $table->string('client_valuation_postcode');
            $table->date('client_valuation_date');
            $table->integer('client_valuation_beds');
            $table->integer('client_valuation_baths');
            $table->integer('property_type_id');
            $table->enum('client_valuation_map', ['y', 'n'])->default('n');
            $table->decimal('client_valuation_latitude', 11, 8)->nullable();
            $table->decimal('client_valuation_longitude', 11, 8)->nullable();
            $table->longText('client_valuation_location_info')->nullable();
            $table->longText('client_valuation_property_description')->nullable();
            $table->integer('client_valuation_price');
            $table->enum('client_valuation_status', ['pending', 'instructed'])->default('pending');
            $table->date('client_valuation_instructed_date')->nullable();
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
        Schema::dropIfExists('client_valuations');
    }
}
