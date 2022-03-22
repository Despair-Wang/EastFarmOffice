<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getGood()
    {
        $this->belongsTo(Good::class, 'id', 'goodId')->withDefault();
    }

    public function getStock()
    {
        $stock = GoodStock::Select('stock')->where('goodId', '=', $this->goodId)->where('goodType', '=', $this->type)->orderBy('updated_at', 'desc')->limit(1)->first();
        if (is_null($stock)) {
            return -1;
        } else {
            return $stock['stock'];
        }

    }
}