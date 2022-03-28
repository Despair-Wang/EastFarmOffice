<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasFactory;

    protected $guarded = ['serial'];

    public function getCoverAttribute($cover)
    {
        return str_replace('public', 'storage', $cover);
    }

    public function getGalleryAttribute($gallery)
    {
        return unserialize(base64_decode($gallery));
    }

    public function getTypes()
    {
        return $this->hasMany(GoodType::class, 'goodId', 'id');
    }

    public function getCategory()
    {
        return $this->hasOne(GoodCategory::class, 'id', 'category')->withDefault();
    }

    public function getState()
    {
        $state = $this->state;
        if ($state == 1) {
            return '上架中';
        } else if ($state == 0) {
            return '已下架';
        }
    }
}