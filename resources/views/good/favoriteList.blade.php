@extends('layouts.basic')
@section('title','我的最愛商品清單')
@section('h1','我的最愛商品清單')
@section('content')
<div class="row">
    @forelse ($goodList as $good)
    @php
        $good = $good->getGood;
    @endphp
        <div class="col-md-12 col-lg-3 p-2 favoriteList">
            <div class="curP" onclick="location.href='{{ url('/o/good/' . $good->serial)  }}'">
                <img class="img-field" src="{{ $good->cover }}">
                <h4 class="h4">{{ $good->name }}</h4>
            </div>
        </div>
    @empty
    <h4 class="h4">尚未加入任何商品</h4>
    @endforelse
</div>
@endsection
