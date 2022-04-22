@extends('layouts.basic')
@section('title','訂單一覽')
@section('h1','訂單一覽')
@section('content')
<div id="orderListBox">
    <div class="row">
        <div class="col-6 col-md-3">
            <label class="w-100">開始時間：</label>
            @if (is_null($start) || $start == 'null')
            <input class="w-100" type="date" id="start">
            @else
            <input class="w-100" type="date" id="start" value="{{ $start }}">
            @endif
        </div>
        <div class="col-6 col-md-3">
            <label class="w-100">結束時間：</label>
            @if (is_null($end) || $end == 'null')
            <input class="w-100" type="date" id="end">
            @else
            <input class="w-100" type="date" id="end" value="{{ $end }}">
            @endif
        </div>
        <div class="col-4 col-md-2">
            <labeL class="w-100">訂單狀態：</label>
            <select id="state">
                <option value="all">全部</option>
                @foreach ($status as $s)
                @if($state == (string)($s->stateId))
                    <option value="{{ $s->stateId }}" selected >{{ $s->name }}</option>
                @else
                    <option value="{{ $s->stateId }}">{{ $s->name }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="col-4 col-md-2">
            <label class="w-100">顯示頁數：</label>
            <select id="page">
                @foreach (['15','30','45','60'] as $i)
                    @if($page == $i)
                    <option value="{{ $i }}" selected>{{ $i }}</option>
                    @else
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-4 col-md-2 d-flex justify-center align-items-end">
            <button id="filter" class="btn btn-primary">篩選訂單</button>
        </div>
    </div>
    <div class="row my-3">
        <div class="col-6 col-md-3">訂單編號</div>
        <div class="col-6 col-md-4">第一項購買商品</div>
        <div class="col-6 col-md-3">下單時間</div>
        <div class="col-6 col-md-2">訂單狀態</div>
    </div>
    @forelse ($orders as $o)
        <div class="row boxItem curP" onclick="location.href='{{ url('/order') . '/' . $o->serial }}'">
            <div class="col-6 col-md-3">{{ $o->serial }}</div>
            <div class="col-6 col-md-4">{{ ($o->getDetails)[0]->getName() }}</div>
            <div class="col-6 col-md-3">{{ $o->getCreateTime() }}</div>
            <div class="col-6 col-md-2">{{ $o->getState() }}</div>
        </div>
    @empty
        <p>尚無訂單，去購物吧</p>
    @endforelse
</div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/good/order/list.js')}}"></script>
@endsection
