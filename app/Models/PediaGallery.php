<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PediaGallery extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getItem()
    {
        return $this->belongsToMany(PediaItem::class, 'itemId', 'fatherId');
    }
}