@extends('layouts.basic')
@section('title','訂單確認')
@section('h1','訂單確認')
@section('content')
<div class="row">
    <div class="col-8 offset-md-2">
        <div id="orderCheckbox" class="">
            @if (Auth::check())
            @php
                $count = 0;
            @endphp
            @foreach (Cache::get(Auth::id()) as $item)
                <div class="checkItem row mb-3" data-index="{{ $count }}">
                    <div class="col-12 col-md-4">{{ $item[2] . '-' . $item[3] }}</div>
                    <div class="col-6 col-md-3 price">$ {{ $item[5] }}</div>
                    <div class="col-5 col-md-2 number">X{{ $item[4] }}</div>
                    <div class="col-5 col-md-2 sum">$ </div>
                    <div class="col-1 col-md-1 text-right"><i class="fa fa-times curP delete" aria-hidden="true"></i></div>
                </div>
                @php
                    $count++;
                @endphp
            @endforeach
            <div class="row">
                <div class="col-12 text-right">
                    <h4 id="total" class="h4">Total </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-6 pr-1">
                    <button class="btn btn-primary w-100">結帳</button>
                </div>
                <div class="col-6 pl-1">
                    <button class="btn btn-primary w-100">繼續購買</button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('customJsBottom')
<script>
    $(()=>{
        var total = 0;
        $('.checkItem').each(function(){
            let t = $(this),
            number = parseInt((t.find('.number').html()).replace('X','')),
            price = parseInt((t.find('.price').html()).replace('$ ',''));
            temp = number * price;
            total += temp;
            t.find('.sum').html('$ ' + temp);
        })
        $('#total').html('Total ' + total);
    })
</script>
@endsection
