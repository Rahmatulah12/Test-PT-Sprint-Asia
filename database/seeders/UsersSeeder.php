<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "role_id" => 1,
                "username" => 'superadmin',
                "email" => "superadmin@gmail.com",
                "password" => Hash::make("password"),
            ],
            [
                "role_id" => 2,
                "username" => 'kasir',
                "email" => "kasir@gmail.com",
                "password" => Hash::make("password"),
            ],

            [
                "role_id" => 3,
                "username" => 'server',
                "email" => "server@gmail.com",
                "password" => Hash::make("password"),
            ],

            [
                "role_id" => 4,
                "username" => 'koky',
                "email" => "koky@gmail.com",
                "password" => Hash::make("password"),
            ],
        ];

        DB::table('users')->insert($data);
    }
}
