<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $timestamp = Carbon::now();

        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'guard_name' => 'sanctum',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Supervisor',
                'guard_name' => 'sanctum',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'User',
                'guard_name' => 'sanctum',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ]);
    }
}
