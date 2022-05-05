@extends('layouts.basic');
@section('title','結帳完成')
@isset($order)
@section('h1','感謝您的購買！')
@endisset
@section('content')
    @if(isset($order))
        <div class="row">
            <div class="col-12 text-right">
                <button class="btn btn-primary" onclick="window.print()">列印</button>
            </div>
            <div class="col-12">
                <h4 class="h4">
                    訂單編號：{{$order->serial}}
                </h4>
            </div>
            <div class="col-12">
                <h4 class="h4 my-2">
                    下單時間：{{$order->getCreateTime()}}
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
                    總金額：{{ $order->total }}
                </h4>
            </div>
            <div class="col-12">
                <p class="my-3">
                    若您採用銀行轉帳，請於7天之內將款項匯至本公司提供的帳號中，並於訂單詳細頁面回傳您的匯款/轉帳資料。<br>
                    確認款項無誤後，我們會盡快出貨！
                </p>
            </div>
            <div class="col-12">
                <p>匯款資訊</p>
                <p>000 XX銀行</p>
                <p>12345456 43211232</p>
                <p>戶名：東鄉事業股份有限公司</p>
            </div>
        </div>
    @else
        <p>您不該出現在此處...</p>
    @endif
    <a href="{{ route('home') }}" class="btn btn-primary w-100 mt-5">返回首頁</a>
@endsection
