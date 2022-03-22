@extends('layouts.backend')
@section('title','庫存管理')
@section('h1',$good->name . '之庫存')
@section('content')
    <div class="row text-center">
        <div class="col-8 col-md-3">款式名稱</div>
        <div class="col-4 col-md-3">現有庫存量</div>
        <div class="col-12 col-md-6">進出貨</div>
    </div>
    @foreach ($types as $type)
    <div class="row typeBox">
        <div class="col-8 col-md-3">
            <h4 class="h4 text-center w-100">{{ $type->name }}</h4>
        </div>
        <div class="col-4 col-md-3">
            <h5 class="h5 text-center w-100">{{ $type->getStock()}}個</h5>
        </div>
        <div class="col-12 col-md-6">
            <input type="number" class="ioControl mr-5">
            <input type="checkbox" class="io" >
        </div>
    </div>
    @endforeach
@endsection
@section('customJsBottom')
<script>
    $(()=>{
        $('.io').lc_switch('進貨','出貨');
    })
</script>
@endsection
