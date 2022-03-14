<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getCategory()
    {
        return $this->hasOne(PostCategory::class, 'id', 'category')->withDefault();
    }

    public function getTags()
    {
        return $this->hasMany(Tag_for_post::class, 'postId', 'id');
    }

    public function getCreateDay()
    {
        return substr($this->created_at, 0, 10);

    }

}