@extends('layouts.backend')
@section('title','商品屬性編輯')
@section('h1','商品屬性編輯')
@section('content')
<div>
    <label>分類名稱</label>
    <input type="text" id="tagName"
    @isset($tag)
        value="{{ $tag->name }}"
    @endisset
    >
</div>
<div class="ali-r mt-3">
    @isset($tag)
    <input type="hidden" id="action" value="update">
    <input type="hidden" id="tagId" value="{{ $tag->id }}">
    @endisset
    <button id="submit" class="btn btn-outline-primary">送出</button>
    <button id="reset" class="btn btn-outline-primary">重寫</button>
</div>
<div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script type="text/javascript" src="{{ asset('js/good/tag/edit.js')}}"></script>
@endsection
