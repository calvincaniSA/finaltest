<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogEntry extends Model
{
    use HasFactory;
    protected $fillable = ['blog_id', 'title', 'content'];
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
