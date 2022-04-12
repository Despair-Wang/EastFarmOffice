@extends('layouts.backend')
@section('title','庫存管理')
@section('h1',$good->name . '之庫存')
@section('content')
    <div class="row text-center">
        <div class="col-8 col-md-3"><h2 class="h2">款式名稱</h2></div>
        <div class="col-4 col-md-3"><h2 class="h2">現有庫存量</h2></div>
        <div class="col-12 col-md-6"><h2 class="h2">進出貨</h2></div>
    </div>
    @foreach ($types as $type)
    <div class="row typeBox" data-good-id="{{ $type->goodId }}" data-type="{{ $type->type }}">
        <div class="col-8 col-md-3">
            <h4 class="h4 text-center w-100">{{ $type->name }}</h4>
        </div>
        <div class="col-4 col-md-3">
            <h5 class="h5 text-center w-100">{{ $type->getStock()}}個</h5>
        </div>
        <div class="col-12 col-md-6">
            <input type="number" class="ioControl mr-5" value="0">
            <input type="checkbox" class="io" checked="checked">
        </div>
    </div>
    @endforeach
    <div class="row">
        <div class="col-6 p-3"><button id="submit" class="btn btn-primary w-100">更改庫存</button></div>
        <div class="col-6 p-3"><button id="reset" class="btn btn-primary w-100">重填</button></div>
    </div>
    <div id="goBack"></div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/good/backend/stockChange.js')}}"></script>
@endsection
