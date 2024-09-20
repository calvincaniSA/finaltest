<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['blog_entry_id', 'user_id', 'content'];
    public function blogEntry()
    {
        return $this->belongsTo(BlogEntry::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
