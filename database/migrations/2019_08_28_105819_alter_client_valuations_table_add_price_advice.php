<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientValuationsTableAddPriceAdvice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_valuations', function (Blueprint $table)
        {
            $table->string('client_valuation_price_advice')->after('client_valuation_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_valuations', function (Blueprint $table)
        {
            $table->dropColumn('client_valuation_price_advice');
        });
    }
}
