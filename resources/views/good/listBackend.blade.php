@extends('layouts.backend')
@section('title','訂單一覽')
@section('h1','訂單一覽')
@section('content')
    <div>
        <div class="row">
            <div class="col-6 col-md-3">
                開始時間：
                @if (is_null($start) || $start == 'null')
                <input type="date" id="start">
                @else
                <input type="date" id="start" value="{{ $start }}">
                @endif
            </div>
            <div class="col-6 col-md-3">
                結束時間：
                @if (is_null($end) || $end == 'null')
                <input type="date" id="end">
                @else
                <input type="date" id="end" value="{{ $end }}">
                @endif
            </div>
            <div class="col-4 col-md-2">
                訂單狀態：
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
                顯示頁數：
                <select id="page">
                    @foreach (['15','30','45','60'] as $i)
                        @if($page == $i)
                        <option value="{{ $i }}" selected>{{ $i }}</option>
                        @else
                        <option value="{{ $i }}">{{ $i }}</option>
                        @endif
                    @endforeach
                    {{-- <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                    <option value="60">60</option> --}}
                </select>
            </div>
            <div class="col-4 col-md-2">
                <button id="filter" class="btn btn-primary">篩選訂單</button>
            </div>
        </div>
        <div class="row">
            <div class="col-10 col-md-4">訂單編號</div>
            <div class="col-2 col-md-2">訂購人</div>
            <div class="col-6 col-md-3">下單時間</div>
            <div class="col-6 col-md-3">訂單狀態</div>
        </div>
        @foreach ($orders as $o)
        <div class="row orderList curP" onclick="location.href='{{ url('/good/order') . '/' . $o->serial }}'">
            <div class="col-10 col-md-4">{{ $o->serial }}</div>
            <div class="col-2 col-md-2">{{ $o->name }}</div>
            <div class="col-6 col-md-3">{{ $o->getCreateTime() }}</div>
            <div class="col-6 col-md-3">{{ $o->getState() }}</div>
        </div>
        @endforeach
    </div>
@endsection
@section('customJsBottom')
<script>
    $(()=>{
        $('#filter').click(function(){
            let start = $('#start').val(),
                end = $('#end').val(),
                page = $('#page').find(':selected').val(),
                state = $('#state').find(':selected').val();
            if(start == ''){
                start = null;
            }
            if(end == ''){
                end = null;
            }

            if(start == null && end != null){
                alert('請選擇開始範圍')
            }else if(start != null && end == null){
                alert('請選擇結束範圍')
            }else if(start != null && end != null){
                if(end >= start){
                    location.href =`/good/order/list/${start}/${end}/${page}/${state}`;
                }else{
                    alert('結束日期不可小於開始日期')
                }
            }else{
                location.href =`/good/order/list/${start}/${end}/${page}/${state}`;
            }
        })
    })
</script>
@endsection
