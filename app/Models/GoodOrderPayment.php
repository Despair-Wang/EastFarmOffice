<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodOrderPayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getOrder()
    {
        return $this->belongsTo(GoodOrder::class, 'pay', 'id');
    }
}