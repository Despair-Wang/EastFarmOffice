<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getPost()
    {
        return $this->belongsTo(Post::class, 'category', 'id')->withDefault();
    }
}