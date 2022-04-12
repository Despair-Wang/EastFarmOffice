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
        <input id="zipcode" type="text" value="{{ $order->zipcode }}">
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
            <div class="col-4">$
                <input type="number" class="amount" value="{{ $d->amount }}">
            </div>
            <div class="col-3">x
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
    <div class="col-12">$
        <input type="number" id="freight" value="{{ $order->freight }}">
    </div>
    <div class="col-12">
        <h4 class="h4">
            總金額
        </h4>
    </div>
    <div class="col-12">
        <input type="number" id="total" value="{{ $order->total }}">
        <button id="sum" class="btn btn-primary w-100">重新計算總金額</button>
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
