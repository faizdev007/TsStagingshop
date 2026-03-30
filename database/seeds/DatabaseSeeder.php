<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            RolesTableSeeder::class,
            PropertyTypesTableSeeder::class,
            PagesTableSeeder::class,
            SlidesTableSeeder::class,

            PropertiesTableSeeder::class,
            //TestimonialsTableSeeder::class,
            PostsTableSeeder::class,
            //SubscribersTableSeeder::class,
            //EnquiriesTableSeeder::class,
        ]);
    }
}
