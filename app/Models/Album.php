<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Album extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getCreateDay()
    {
        return substr($this->created_at, 0, 10);
    }

    public function getCover()
    {
        if (is_null($this->cover)) {
            return '<img src="/assets/album/albumDefault.png">';
        } else {
            if (substr($this->cover, 0, 4) == 'http') {
                return '<img src="' . $this->cover . '">';
            } else if (substr($this->cover, 0, 7) == '/assets') {
                return '<img src="/assets/album/albumDefault.png">';
            } else {
                return '<img src="' . url(Storage::url($this->cover)) . '">';
            }
        }
    }
}