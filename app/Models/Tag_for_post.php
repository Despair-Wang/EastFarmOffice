<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag_for_post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getPost()
    {
        return $this->belongsTo(Post::class, 'postId', 'id')->withDefault();
    }

    public function getTag()
    {
        return $this->belongsTo(PostTag::class, 'tagId', 'id')->withDefault();
    }
}