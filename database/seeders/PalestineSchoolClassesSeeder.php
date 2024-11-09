<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PalestineSchoolClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        $grades = [

            ['grade_name' => 'الصف الأول', 'grade_level' => 1 ,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف الثاني', 'grade_level' => 2,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف الثالث', 'grade_level' => 3,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف الرابع', 'grade_level' => 4,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف الخامس', 'grade_level' => 5,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف السادس', 'grade_level' => 6,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف السابع', 'grade_level' => 7,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف الثامن', 'grade_level' => 8,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف التاسع', 'grade_level' => 9,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف العاشر', 'grade_level' => 10,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف الحادي عشر', 'grade_level' => 11,'created_at' => $now, 'updated_at' => $now],
            ['grade_name' => 'الصف الثاني عشر', 'grade_level' => 12,'created_at' => $now, 'updated_at' => $now],
            ];

        DB::table('school_classes')->insert($grades);
    }
}
