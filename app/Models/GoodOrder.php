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

    public function getInvoiceType()
    {
        $invoiceType = $this->invoiceType;
        $result = '無資料';
        switch ($invoiceType) {
            case 'twoPart':
                $result = '二聯式發票';
                break;
            case 'triplePart':
                $result = '三聯式發票';
                break;
            case 'donate':
                $result = '捐贈發票';
                break;
        }
        return $result;
    }

    public function getInvoiceSendType()
    {
        $invoiceSendType = $this->invoiceSendType;
        $result = '無資料';
        switch ($invoiceSendType) {
            case 'withGood':
                $result = '隨貨寄送發票';
                break;
            case 'another':
                $result = '指定寄送地址';
                break;
        }
        return $result;
    }

    public function restockNotice()
    {
        //
    }
}