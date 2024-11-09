<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = ['semester_name', 'grade_level'];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'grade_level', 'id');
    }


    public function subjects()
    {
        return $this->hasMany(Subject::class, 'grade_level', 'grade_level');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id'); // 'subject_id' هو المفتاح الأجنبي في جدول الفصول الدراسية (semesters) الذي يشير إلى الجدول subjects
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
