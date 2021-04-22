<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
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
                "name" => 'superadmin'
            ],
            [
                "name" => 'kasir'
            ],
            [
                "name" => 'server'
            ],
            [
                "name" => 'koky'
            ],
        ];

        DB::table('roles')->insert($data);
    }
}
