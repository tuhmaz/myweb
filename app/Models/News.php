<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class News extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'author_id','category_id', 'keywords', 'meta_description','image', 'alt'];


    public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}

public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'news_keyword', 'news_id', 'keyword_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }


}
