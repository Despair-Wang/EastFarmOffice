<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodTag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getGoods()
    {
        return $this->hasMany(Tag_for_good::class, 'tagId', 'id');
    }
}