<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = ['name'];


    public function schoolClasses()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class, 'grade_level', 'grade_level');
    }

}
