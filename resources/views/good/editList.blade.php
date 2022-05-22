@extends('layouts.backend')
@section('title','商品一覽')
@section('h1','商品一覽')
@section('content')
    <div id="gooodList">
    @forelse ($goods as $good)
        <div class="goodListBox row mb-2" data-good-id="{{ $good->serial }}">
                <div class="col-1">
                    <img src="{{ $good->cover }}">
                </div>
                <div class="col-6">
                    <h6 class="h6">{{ $good->serial }}</h6>
                    <h3 class="h3">{{ $good->name }}<span style="font-size:1rem;color:#666">({{$good->getState()}})</span></h3>
                </div>
                <div class="col-5 flex align-items-center justify-content-end">
                    <button class="btn btn-info px-4 mr-3 edit">修改</button>
                    <button class="btn btn-info px-4 mr-3 stock">庫存</button>
                    @if ($good->state == 1)
                    <button class="btn btn-info px-4 mr-3 putdown">下架</button>
                    @else
                    <button class="btn btn-info px-4 mr-3 putUp">上架</button>
                    @endif
                    <button class="btn btn-info px-4 delete">刪除</button>
                </div>
            </div>
            @empty
                <h3 class="h3">無商品</h3>
            @endforelse
        {!! $goods->links() !!}
    </div>
    <div id="createNew"></div>
@endsection
@section('customJsBottom')
<script type="text/javascript" src="{{ asset('js/good/backend/list.js')}}"></script>
@endsection
