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

    public function getCategoryAttribute($category)
    {
        return PediaCategory::find($category)->get()->first()->name;
    }

    public function getContents()
    {
        return $this->hasMany(PediaContent::class, 'itemId', 'fatherId');
    }

    public function getGalleries()
    {
        return $this->hasMany(PediaGallery::class, 'itemId', 'fatherId');
    }
}