<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PediaItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getCategory()
    {
        return $this->hasOne(PediaCategory::class, 'id', 'category')->withDefault();
    }

    public function getTags()
    {
        return $this->hasMany(Tag_for_pedia::class, 'itemId', 'id');
    }

    public function getImageAttribute($image)
    {
        return str_replace('public', 'storage', $image);
    }

    public function getCategoryName()
    {
        return PediaCategory::Where('id', $this->category)->first()->name;
    }

    public function getContents()
    {
        return $this->hasMany(PediaContent::class, 'itemId', 'id');
    }

    public function getGalleries()
    {
        return $this->hasMany(PediaGallery::class, 'itemId', 'id');
    }
}