<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PediaTagType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getTagForItem($item)
    {
        return Tag_for_pedia::Join('pedia_tags', 'tag_for_pedias.tagId', '=', 'pedia_tags.id')->Where('pedia_tags.typeId', $this->id)->Where('tag_for_pedias.itemId', $item)->get();
    }
}