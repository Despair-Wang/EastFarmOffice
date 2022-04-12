@extends('layouts.basic')
@section('title','訂單編號-' . $order->serial)
@section('h1','訂單編號-' . $order->serial)
@section('content')
<div id="orderDetailBox" class="row" data-serial="{{ $order->serial }}">
    <div class="col-12">
        <h4 class="h4">
            訂單狀態
        </h4>
    </div>
    <div class="col-12">
        <h4 class="h4 float-left">
            {{ $order->getState() }}
        </h4>
        @if ($order->state == '1')
        <button id="reportBtn" class="btn btn-info float-right">通知已付款</button>
        @endif
    </div>
    @if ($order->state == '4')
    <div class="col-12">
        <h4 class="h4">
            付款資訊
        </h4>
    </div>
    <div class="col-12">
        <h4 class="h4">
            {{ '末五碼:' . $order->payAccount . ' / 金額:$' . $order->payAmount . ' / 時間:' . $order->payTime }}
        </h4>
    </div>
    @endif
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
            總金額
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
<div id="goBack"></div>
<div id="report">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="w-100 text-right"><i id="closeWindow" class="fa fa-times curP scale2" aria-hidden="true"></i></div>
            </div>
            <div class="col-12">
                <h2 class="h2 w-100 text-center">通知付款</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-3">
                <label>匯款戶名(可空白)</label>
                <input class="w-100" type="text" id="name">
            </div>
            <div class="col-12 col-md-3">
                <label>帳號末5碼</label>
                <input class="w-100" type="text" id="account" maxlength="5">
            </div>
            <div class="col-12 col-md-3">
                <label>付款時間</label>
                <input class="w-100" type="date" id="day">
                <select id="time" class="w-100">
                    @for ($i = 0; $i < 24; $i++)
                        @if ($i < 10 && $i+1 < 10)
                        <option value="0{{ $i }}:00~0{{ $i+1 }}:00">0{{ $i }}:00~0{{ $i+1 }}:00</option>
                        @elseif ($i < 10 && $i+1 >= 10)
                        <option value="0{{ $i }}:00~{{ $i+1 }}:00">0{{ $i }}:00~{{ $i+1 }}:00</option>
                        @else
                            @if($i==23)
                            <option value="{{ $i }}:00~00:00">{{ $i }}:00~00:00</option>
                            @else
                            <option value="{{ $i }}:00~{{ $i+1 }}:00">{{ $i }}:00~{{ $i+1 }}:00</option>
                            @endif
                        @endif
                    @endfor
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label>金額</label>
                <input class="w-100" type="number" id="amount">
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-right">
                <button id="submit" class="btn btn-outline-primary">通知已付款</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/good/order/show.js')}}"></script>
@endsection
