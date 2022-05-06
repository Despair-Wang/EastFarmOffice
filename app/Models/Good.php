<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function getTags()
    {
        return $this->hasMany(GoodTag::class, 'goodId', 'id');
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

    public function getFavorites()
    {
        return $this->hasMany(GoodFavorite::class, 'goodId', 'id');
    }

    public function checkFavorite()
    {
        if (Auth::check()) {
            $user = Auth::id();
            $result = GoodFavorite::Where('userId', $user)->Where('goodId', $this->id)->get();
            if (count($result) == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}