<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getOrder()
    {
        return $this->belongsTo(GoodOrder::class, 'id', 'orderId')->withDefault();
    }

    public function getName()
    {
        $goodId = $this->goodId;
        $good = (Good::Where('id', $goodId)->first())['name'];
        return $good;
    }

    public function getType()
    {
        $goodId = $this->goodId;
        $type = (GoodType::Where('goodId', $goodId)->Where('type', $this->type)->first())['name'];
        return $type;

    }
}