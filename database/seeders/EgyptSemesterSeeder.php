<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EgyptSemesterSeeder extends Seeder
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
        $school_classes = DB::connection('eg')->table('school_classes')->get(); // اتصال بقاعدة مصر

        foreach ($school_classes as $class) {
            foreach ($semesters as $semester_name) {
                DB::connection('eg')->table('semesters')->insert([
                    'semester_name' => $semester_name,
                    'grade_level' => $class->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
