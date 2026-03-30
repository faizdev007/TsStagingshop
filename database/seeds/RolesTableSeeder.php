<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert(
            [
                [
                    'role_title'    => 'Super Admin',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ],
                [
                    'role_title'    => 'Admin',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ],
                [
                    'role_title'    => 'Agent',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ],
                [
                    'role_title'    => 'User / Member',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ]
            ]
        );
    }
}
