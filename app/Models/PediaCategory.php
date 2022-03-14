<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PediaCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getItem()
    {
        return $this->belongsTo(PediaItem::class, 'category', 'id')->withDefault();
    }
}