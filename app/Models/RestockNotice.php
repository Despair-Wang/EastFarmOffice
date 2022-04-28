<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockNotice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'UserId')->withDefault();
    }
}