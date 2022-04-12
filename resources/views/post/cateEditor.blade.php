@extends('layouts.backend')
@section('title', '文章分類編輯')
@section('h1', '文章分類編輯')
@section('content')
    <div>
        <label>分類名稱</label>
        <input type="text" id="cateName"
        @isset($cate)
            value="{{ $cate->name }}"
        @endisset
        >
    </div>
    <div>
        <label>分類說明</label>
        <input type="text" id="cateContent"
        @isset($cate)
            value="{{ $cate->content }}"
        @endisset
        >
    </div>
    <div class="ali-r mt-3">
        @isset($cate)
        <input type="hidden" id="action" value="update">
        <input type="hidden" id="cateId" value="{{ $cate->id }}">
        @endisset
        <button id="submit" class="btn btn-outline-primary">送出</button>
        <button id="reset" class="btn btn-outline-primary">重寫</button>
    </div>
@endsection
@section('customJsBottom')
    <script type="text/javascript" src="{{ asset('js/post/category/edit.js')}}"></script>
@endsection
