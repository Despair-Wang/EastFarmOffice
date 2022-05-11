<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag_for_good extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getGood()
    {
        return $this->belongsTo(Good::class, 'goodId', 'id');
    }

    public function getTag()
    {
        return $this->belongsTo(GoodTag::class, 'tagId', 'id');
    }
}