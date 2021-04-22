<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            [
                "name" => "makanan"
            ],
            [
                "name" => "minuman"
            ]
        ];
        DB::table('category_menus')->insert($data);
    }
}
