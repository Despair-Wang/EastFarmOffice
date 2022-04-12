@extends('layouts.basic')
@section('title','加入購物車')
@section('h1','加入購物車')
@section('content')
<div id="typeSelect" data-good-id="{{ $good->id }} " data-good-serial="{{ $good->serial }}">
    <div>
        <div class="row">
            <div class="col-6 col-md-4">
                <img src="{{$good->cover}}" alt="">
            </div>
            <div class="col-6 col-md-8">
                <h4 class="h4">{{ $good->name }}</h4>
            </div>
            <div class="col-12">
                <h2 class="h2 w-100 text-center">購買數量</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-4">款式</div>
            <div class="col-8">數量</div>
        </div>
        @foreach ($good->getTypes as $type)
        <div class="row selectItem" data-type="{{ $type->type }}">
            <div class="col-4">{{ $type->name }}</div>
            <div class="col-8">
                <div class="orderNumber">
                    <div class="curP reduceNum">
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </div>
                    <div><input type="number" class="order" value="0"></div>
                    <div class="curP addNum">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="col-12 text-right">
                <button id="submit" class="btn btn-outline-primary">加入購物車</button>
            </div>
        </div>
    </div>
</div>
<div id="goBack"></div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/good/addCart.js')}}"></script>
@endsection
