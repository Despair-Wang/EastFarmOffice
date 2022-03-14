<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getPost()
    {
        return $this->hasMany(Tag_for_post::class, 'tagId', 'id');
    }
}
