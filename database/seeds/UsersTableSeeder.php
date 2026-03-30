<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This will add a few agents
     *
     * @return void
     */
    public function run()
    {
        /*----------------------------------------------------
        * Required User
        */

        $user = factory(App\User::class)->create([
            'name' => 'Super Admin',
            'email' => 'hello@terezaestates.com',
            'telephone' => '+441429450510',
            'status' => 1,
            'role_id' => 1,
            'password' => bcrypt('PW5565**'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $user = factory(App\User::class)->create([
            'name' => 'Admin',
            'email' => 'hello2@terezaestates.com',
            'telephone' => '+441429450510',
            'status' => 1,
            'role_id' => 2,
            'password' => bcrypt('PW5565**'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $user = factory(App\User::class)->create([
            'name' => 'Agent User',
            'email' => 'hello3@terezaestates.com',
            'telephone' => '+441429450510',
            'status' => 1,
            'role_id' => 3,
            'password' => bcrypt('PW5565**'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if(0):
        /*----------------------------------------------------*/

        // Add Rich as a super user
        $user = factory(App\User::class)->create([
            'name' => 'Rich Heseltine',
            //'first_name' => 'Rich Heseltine',
            //'last_name' => '',
            'email' => 'hello4@terezaestates.com',
            'telephone' => '',
            //'website' => 'https://www.terezaestates.com',
            //'role' => 'super',
            'status' => 1,
            'role_id' => 1,
            'password' => bcrypt('PW5565**'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // Add Jan as a super user
        $user = factory(App\User::class)->create([
            'name' => 'Jan Parker',
            'email' => 'hello5@terezaestates.com',
            'telephone' => '',
            'role_id' => 1,
            'status' => 1,
            'password' => bcrypt('PW5565**'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // Add Jexson as a super user
        $user = factory(App\User::class)->create([
            'name' => 'JJ Property',
            'email' => 'hello6@terezaestates.com',
            'telephone' => '',
            'role_id' => 1,
            'status' => 1,
            'password' => bcrypt('PW5565**'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // Add Stuart as a super user
        $user = factory(App\User::class)->create([
            'name' => 'Stuart Blackett',
            'email' => 'hello7@terezaestates.com',
            'telephone' => '',
            'role_id' => 1,
            'status' => 1,
            'password' => bcrypt('PW5565**'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        endif;


        // Add 20 test agents
        /*
        $users = factory(App\User::class, 100)->create([
            'status' => 1,
            'role' => 'agent',
            'password' => bcrypt('PW5565**'),
        ]);
        */
    }
}
