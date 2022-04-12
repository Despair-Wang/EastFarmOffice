@extends('layouts.backend')
@section('title','訂單' . $order->serial)
@section('h1','訂單' . $order->serial)
@section('content')
    <div id="orderDetailBox" class="row" data-serial="{{ $order->serial }}">
        <div class="col-12">
            <h4 class="h4">
                訂單狀態
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                {{ $order->getState() }}
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                訂購人
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                {{ $order->name }}
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                電話
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                {{ $order->tel }}
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                配送地址
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                {{ $order->zipcode }}
            </h4>
            <h4 class="h4">
                {{ $order->address }}
            </h4>
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
            <div class="row">
                @foreach ($order->getDetails as $d)
                <div class="col-4">
                    <h5 class="h5">{{ $d->getName() }}</h5>
                    <h6 class="h6">{{ $d->getType() }}</h6>
                </div>
                <div class="col-4">$ {{ $d->amount }}</div>
                <div class="col-4">x{{ $d->quantity }}</div>
                @endforeach
            </div>
        </div>
        <div class="col-12">
            <h4 class="h4">
                運費
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                {{ $order->freight }}
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                總金額
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                {{ $order->total }}
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                付款方式
            </h4>
        </div>
        <div class="col-12">
            <h4 class="h4">
                {{ $order->getPayment->name }}
            </h4>
        </div>
    </div>
    <div class="row">
        @if ($order->state == '1')
        <div class="col-12 p-2">
            <button id="edit" class="btn btn-primary w-100">更改訂單</button>
        </div>
        @endif
        <div class="col-4 p-2">
            <button id="paid" class="btn btn-primary w-100">已付款</button>
        </div>
        <div class="col-4 p-2">
            <button id="delivered" class="btn btn-primary w-100">已配送</button>
        </div>
        <div class="col-4 p-2">
            <button id="cancel" class="btn btn-primary w-100">取消訂單</button>
        </div>
    </div>
    <div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script type="text/javascript" src="{{ asset('js/good/order/backend/show.js')}}"></script>
@endsection
