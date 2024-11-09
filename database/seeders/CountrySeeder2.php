<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder2 extends Seeder
{
    public function run()
    {
        DB::table('countries')->insert([
            ['name' => 'Saudi Arabia', 'code' => 'SA'],
            ['name' => 'Egypt', 'code' => 'EG'],
            ['name' => 'Jordan', 'code' => 'JO'],
            ['name' => 'Palestine', 'code' => 'PS'],
        ]);
    }
}
