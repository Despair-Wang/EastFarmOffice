<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag_for_pedia extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getItem()
    {
        return $this->belongsTo(Pedia::class, 'itemId', 'id')->withDefault();
    }

    public function getTag()
    {
        return $this->belongsTo(PediaTag::class, 'tagId', 'id')->withDefault();
    }
}