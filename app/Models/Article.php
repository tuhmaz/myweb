<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Fillable attributes to protect against mass assignment
    protected $fillable = [
        'grade_level',
        'subject_id',
        'semester_id',
        'title',
        'content',
        'meta_description',
        'author_id',
        'visit_count',
    ];

    /**
     * Relationship with SchoolClass based on grade_level
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'grade_level', 'grade_name');
    }

    /**
     * Relationship with Subject model
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relationship with Semester model
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Relationship with File model
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Relationship with User model (as author)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Polymorphic relationship for comments
     * Use default connection 'jo' for comments
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Relationship with Country model
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relationship with Keyword model using a pivot table
     */
    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'article_keyword', 'article_id', 'keyword_id');
    }
}
