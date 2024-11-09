<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;


    protected $fillable = ['file_Name','article_id', 'file_type', 'file_category', 'file_path', 'download_count', 'views_count', ];


    /**
     * Get the article that owns the file.
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

}
