<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusOrdersSeeder extends Seeder
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
                "name"=>"active",
            ],
            [
                "name"=>"inactive",
            ],
        ];

        DB::table('status_orders')->insert($data);
    }
}
