<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PediaTag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getItem()
    {
        return $this->hasMany(Tag_for_pedia::class, 'tagId', 'id');
    }

    public function getType()
    {
        return $this->hasOne(PediaTagType::class, 'id', 'typeId')->withDefault();
    }
}