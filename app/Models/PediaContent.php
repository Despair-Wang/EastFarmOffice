<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PediaContent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getItem()
    {
        return $this->belongsToMany(PediaItem::class, 'itemId', 'fatherId');
    }

    public function getRemark()
    {
        return unserialize(base64_decode($this->remark));
    }

    public function getGallery()
    {
        return unserialize(base64_decode($this->gallery));
    }
}