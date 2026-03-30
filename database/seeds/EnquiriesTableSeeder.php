<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class EnquiriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default enquiries
        $faker = Faker::create();
        foreach (range(1, 100) as $i) {
            DB::table('leads')->insert([
                'uuid' => Str::random(16),
                'name' => $faker->firstName.' '.$faker->lastName,
                'email' => $faker->safeEmail,
                'telephone' => $faker->e164PhoneNumber,
                'category' => 'Contact Us',
                'message' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit',
                'url' => url('contact-us'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
