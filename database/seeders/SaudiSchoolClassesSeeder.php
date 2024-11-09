<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaudiSchoolClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $now = Carbon::now();

      $grades = [

        // الصفوف السعودية
        ['grade_name' => 'الصف الأول الابتدائي', 'grade_level' => 1,  'created_at' => $now, 'updated_at' => $now],
        ['grade_name' => 'الصف الثاني الابتدائي', 'grade_level' => 2,  'created_at' => $now, 'updated_at' => $now],
        ['grade_name' => 'الصف الثالث الابتدائي', 'grade_level' => 3,  'created_at' => $now, 'updated_at' => $now],
        ['grade_name' => 'الصف الرابع الابتدائي', 'grade_level' => 4,  'created_at' => $now, 'updated_at' => $now],
        ['grade_name' => 'الصف الخامس الابتدائي', 'grade_level' => 5,  'created_at' => $now, 'updated_at' => $now],
        ['grade_name' => 'الصف السادس الابتدائي', 'grade_level' => 6,  'created_at' => $now, 'updated_at' => $now],
        ['grade_name' => 'الصف الأول المتوسط', 'grade_level' => 7,  'created_at' => $now, 'updated_at' => $now],
        ['grade_name' => 'الصف الثاني المتوسط', 'grade_level' => 8,  'created_at' => $now, 'updated_at' => $now],
        ['grade_name' => 'الصف الثالث المتوسط', 'grade_level' => 9,  'created_at' => $now, 'updated_at' => $now],
        ['grade_name' => 'المرحلة الثانوية - نظام المقررات', 'grade_level' => 10,  'created_at' => $now, 'updated_at' => $now],
        ['grade_name' => 'المرحلة الثانوية - النظام الفصلي', 'grade_level' => 11,  'created_at' => $now, 'updated_at' => $now],
    ];


    DB::table('school_classes')->insert($grades);
  }
}
