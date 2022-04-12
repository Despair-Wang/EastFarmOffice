@extends('layouts.backend')
@section('title','商品類別一覽')
@section('h1','商品類別一覽')
@section('content')
    <div class="postListBox">
        <div class="row">
            <div class="col-10 row">
                <div class="col-4 col-md-3"><h2 class="h2">分類名稱</h2></div>
                <div class="col-6 col-md-9"><h2 class="h2">所屬分類</h2></div>
            </div>
        </div>
        @forelse ($categories as $g)
            <div id="{{ $g->id }}" class="listBox editPostItem row">
                <div class="col-10 row">
                    <div class="col-4 col-md-3">{{ $g->name }}</div>
                    <div class="col-6 col-md-9">{{ $g->getBelong() }}</div>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary edit">編輯</button>
                    <button class="btn btn-primary delete">刪除</button>
                </div>
            </div>
        @empty
            <p>暫無資料</p>
        @endforelse
    </div>
    <div id="createNew"></div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/good/category/list.js')}}"></script>
@endsection
