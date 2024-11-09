<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; //Soft Deletes
use Spatie\Permission\Traits\HasRoles;

class SchoolClass extends Model
{
    use HasFactory , Notifiable;


    protected $fillable = ['grade_name', 'grade_level', 'country_id'];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'grade_level');
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class, 'grade_level', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    

}
