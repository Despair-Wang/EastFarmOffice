<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodFavorite extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getUsers()
    {
        return $this->belongsTo(User::class, 'userId', 'id')->withDefault();
    }

    public function getGood()
    {
        return $this->belongsTo(Good::class, 'goodId', 'id')->withDefault();
    }
}