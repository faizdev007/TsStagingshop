<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SlidesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default slides
        DB::table('slides')->insert([
            'text_line1' => 'Luxury Real Estate',
            'text_line2' => 'BARBADOS PROPERTY',
            'priority' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('slides')->insert([
            'text_line1' => 'Luxury Real Estate',
            'text_line2' => 'BARBADOS PROPERTY',
            'priority' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
