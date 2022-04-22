@extends('layouts.backend')
@section('title','訂單修改-編號:' . $order->serial)
@section('h1','訂單修改-編號:' . $order->serial)
@section('content')
<div id="orderDetailBox" class="row" data-serial="{{ $order->serial }}">
    <div class="col-12">
        <h4 class="h4">
            訂單狀態
        </h4>
    </div>
    <div class="col-12">
        <select id="state">
            @forelse ($status as $s)
            @if ($order->state == $s->stateId)
                <option value="{{ $s->stateId }}" selected >{{ $s->name }}</option>
            @else
                <option value="{{ $s->stateId }}">{{ $s->name }}</option>
            @endif
            @empty
                <option value="-">NO DATA</option>
            @endforelse
        </select>
    </div>
    <div class="col-12">
        <h4 class="h4">
            訂購人
        </h4>
    </div>
    <div class="col-12">
        <input id="name" type="text" value="{{ $order->name }}">
    </div>
    <div class="col-12">
        <h4 class="h4">
            電話
        </h4>
    </div>
    <div class="col-12">
        <input id="tel" type="text" value="{{ $order->tel }}">
    </div>
    <div class="col-12">
        <h4 class="h4">
            配送地址
        </h4>
    </div>
    <div class="col-12">
        <input id="zipcode" class="mb-2" style="width:auto" maxlength="5" type="text" value="{{ $order->zipcode }}">
        <input id="address" type="text" value="{{ $order->address }}">
    </div>
    <div class="col-12">
        <h4 class="h4">
            下單時間
        </h4>
    </div>
    <div class="col-12">
        <h4 class="h4">
            {{ $order->getCreateTime() }}
        </h4>
    </div>
    <div class="col-12">
        <h4 class="h4">
            訂購內容
        </h4>
    </div>
    <div class="col-11 offset-1">
        @foreach ($order->getDetails as $d)
        <div class="typeBox row" id="{{ $d->id }}">
            <div class="col-4">
                <h5 class="h5">{{ $d->getName() }}</h5>
                <h6 class="h6">{{ $d->getType() }}</h6>
            </div>
            <div class="col-4">
                <label class="mr-2 mb-0">$</label>
                <input type="number" class="amount" value="{{ $d->amount }}">
            </div>
            <div class="col-3">
                <label class="mr-2 mb-0">x</label>
                <input type="number" class="quantity" value="{{ $d->quantity }}">
            </div>
            <div class="col-1">
                <i class="fa fa-times float-right typeDel curP" aria-hidden="true"></i>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-12">
        <h4 class="h4">
            運費
        </h4>
    </div>
    <div class="col-12">
        <div class="d-flex align-items-center">
            <label class="mr-2 mb-0">$</label>
            <input type="number" id="freight" value="{{ $order->freight }}">
        </div>
    </div>
    <div class="col-12">
        <h4 class="h4">
            總金額
        </h4>
    </div>
    <div class="col-12">
        <div class="d-flex align-items-center">
            <label class="mr-2 mb-0">$</label>
            <input type="number" id="total" value="{{ $order->total }}">
        </div>
        <button id="sum" class="btn btn-primary w-100 my-3">重新計算總金額</button>
    </div>
    <div class="col-12">
        <h4 class="h4">
            付款方式
        </h4>
    </div>
    <div class="col-12">
        <select id="payment">
            @foreach ($payments as $p)
            @if ($order->pay == $p->id )
                <option value="{{ $p->id }}" selected>{{ $p->name }}</option>
            @else
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endif
            @endforeach
        </select>
        </h4>
    </div>
    <div class="col-12">
        <h4 class="h4">
            發票類型
        </h4>
    </div>
    <div class="col-12">
    @php
    $receiptType = ['二聯式發票','三聯式發票','捐贈發票'];
    $receiptTypeValue = ['twoPart','triplePart','donate'];
    @endphp
    <select id="receiptType">
        @for ($i = 0; $i < 3; $i++)
            @if($receiptTypeValue[$i] == $order->receiptType)
            <option value="{{ $receiptTypeValue[$i] }}" selected>{{ $receiptType[$i] }}</option>
            @else
            <option value="{{ $receiptTypeValue[$i] }}">{{ $receiptType[$i] }}</option>
            @endif
        @endfor
    </select>
    </div>
    <div class="col-12">
        <h4 class="h4">
            統編
        </h4>
    </div>
    <div class="col-12">
        <input id="taxNumber" type="text" value="{{ $order->taxNumber }}" maxlength="8">
    </div>
    <div class="col-12">
        <h4 class="h4">
            發票寄送方式
        </h4>
    </div>
    <div class="col-12">
    @php
        $receiptSendType = ['隨貨寄送發票','指定寄送地點'];
        $receiptSendTypeValue = ['withGood','another'];
    @endphp
    <select id="receiptSendType">
    @for ($i = 0; $i < 2; $i++)
        @if ($order->receiptSendType == $receiptSendTypeValue[$i])
            <option value="{{ $receiptSendTypeValue[$i] }}" selected>{{ $receiptSendType[$i] }}</option>
        @else
            <option value="{{ $receiptSendTypeValue[$i] }}">{{ $receiptSendType[$i] }}</option>
        @endif
    @endfor
    </select>
    </div>
    <div class="col-12">
        <h4 class="h4">
            寄送地址
        </h4>
    </div>
    <div class="col-12">
        <input id="subZipcode" maxlength="5" type="text" style="width:auto" value="{{ $order->receiptZipcode }}">
        <input id="subAddress" type="text" value="{{ $order->receiptAddress }}">
    </div>
</div>
<div class="row">
    <div class="col-6 p-2">
        <button id="submit" class="btn btn-primary w-100">更改訂單</button>
    </div>
    <div class="col-6 p-2">
        <button id="reset" class="btn btn-primary w-100">恢復</button>
    </div>
</div>
<div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script type="text/javascript" src="{{ asset('js/good/order/backend/edit.js')}}"></script>
@endsection
