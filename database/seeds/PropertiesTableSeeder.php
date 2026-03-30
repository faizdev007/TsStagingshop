<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('en_GB');
        foreach (range(1, 6) as $i) {
            $p_priceQualifiers_array = p_priceQualifiers();
            $p_priceQualifiers_index = shuffle($p_priceQualifiers_array);

            $p_statuses_array = p_statuses();
            $p_statuses_index = shuffle($p_statuses_array);

            DB::table('properties')->insert([
                'user_id' => 102,
                'feed_id' => 0,
                'property_type_id' => 1,
                'ref' => 'PW-'.$faker->unique()->randomNumber(6),
                //'is_rental' => $faker->numberBetween(0, 1),
                'is_rental' => 0,
                //'is_featured' => $faker->numberBetween(0, 1),
                'is_featured' => 0,
                'street' => $faker->streetName,
                'town' => $faker->city,
                'city' => $faker->city,
                'region' => $faker->city,
                'postcode' => $faker->postcode,
                'country' => 'United Kingdom',
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'price' => $faker->numberBetween(50000, 1500000),
                'price_qualifier' => $p_priceQualifiers_array[$p_priceQualifiers_index],
                'beds' => $faker->randomNumber(1),
                'baths' => 0,
                'area' => 0,
                'name' => $faker->streetAddress,
                'status' => 0,
                'property_status' => (strtolower($p_statuses_array[$p_statuses_index]) == 'please select...') ? '' : $p_statuses_array[$p_statuses_index] ,
                'internal_area' => $faker->numberBetween(50, 1250),
                'internal_area_unit' => '',
                'land_area' => $faker->numberBetween(250, 2500),
                'land_area_unit' => '',
                'youtube_id' => 'ilyRQahO25Y',
                'rent_period' => 3,
                'add_info' => 'Pool,Terrace,Tennis,Court,Bar,Restaurant,Gym',
                'description' => '<h3>Living Room</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste </p>
                <h3>Kitchen</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <h3>Bathrooms</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <h3>Bedrooms</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
                'agent_notes' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum in auctor lectus. Pellentesque quis enim convallis, efficitur enim sit amet, ornare orci.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                //'created_at' => '2019-03-13 00:00:00'
            ]);
        }
    }
}
