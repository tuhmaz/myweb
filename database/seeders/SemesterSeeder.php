<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();


        $semesters = ['الفصل الدراسي الأول', 'الفصل الدراسي الثاني'];


        $school_classes = DB::table('school_classes')->get();

        foreach ($school_classes as $class) {
            foreach ($semesters as $semester_name) {
                DB::table('semesters')->insert([
                    'semester_name' => $semester_name,
                    'grade_level' => $class->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
