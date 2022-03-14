<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getUrlAttribute($url)
    {
        if (substr($url, 0, 4) == 'http') {
            return $url;
        } else {
            return url(Storage::url($url));
        }
    }

}