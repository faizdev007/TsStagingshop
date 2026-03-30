<?php

use App\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prop = factory(App\PropertyType::class)->create([
            'name' => 'Apartment',
            'slug' => 'apartment',
            'rightmove_number' => '28',
            'zoopla_type'      => 'flat'
        ]);
        $prop = factory(App\PropertyType::class)->create([
            'name' => 'Villa',
            'slug' => 'villa',
            'rightmove_number' => '27',
            'zoopla_type'      => 'villa'
        ]);
        $prop = factory(App\PropertyType::class)->create([
            'name' => 'Semi-Detached House',
            'slug' => 'semi-detached-house',
            'rightmove_number' => '3',
            'zoopla_type'      => 'semi_detached'
        ]);
        $prop = factory(App\PropertyType::class)->create([
            'name' => 'Detached House',
            'slug' => 'detached-house',
            'rightmove_number' => '4',
            'zoopla_type'      => 'detached'
        ]);
        $prop = factory(App\PropertyType::class)->create([
            'name' => 'Terraced House',
            'slug' => 'terraced-house',
            'rightmove_number' => '1',
            'zoopla_type'      => 'terraced'
        ]);
        $prop = factory(App\PropertyType::class)->create([
            'name' => 'House',
            'slug' => 'house',
            'rightmove_number' => '26',
            'zoopla_type'      => 'town_house'
        ]);
        $prop = factory(App\PropertyType::class)->create([
            'name' => 'Commercial Property',
            'slug' => 'commercial-property',
            'rightmove_number' => '244',
            'zoopla_type'      => 'retail'
        ]);
        $prop = factory(App\PropertyType::class)->create([
            'name' => 'New Development',
            'slug' => 'new-development',
            'rightmove_number' => '250',
            'zoopla_type'      => ''
        ]);
    }
}
