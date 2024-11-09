<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes
use Spatie\Permission\Traits\HasRoles;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['grade_level', 'subject_name'];



    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'grade_level', 'grade_level');
    }


    public function semesters()
    {
        return $this->hasMany(Semester::class, 'grade_level', 'grade_level');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'subject_id');
    }
}
