<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodOrder extends Model
{
    use HasFactory;

    protected $guarded = ['serial'];

    public function getCreateTime()
    {
        return substr($this->created_at, 0, 19);
    }

    public function getState()
    {
        $state = $this->state;
        $result = GoodOrderState::Where('stateId', $state)->first();
        return $result['name'];
    }

    public function getDetails()
    {
        return $this->hasMany(GoodDetail::class, 'orderId', 'id');
    }

    public function getPayment()
    {
        return $this->hasOne(GoodOrderPayment::class, 'id', 'pay')->withDefault('No Target');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}