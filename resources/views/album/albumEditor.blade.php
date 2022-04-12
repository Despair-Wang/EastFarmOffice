@extends('layouts.backend')
@if(isset($album))
    @section('title','相簿更新')
    @section('h1','相簿更新')
@else
    @section('title','相簿建立')
    @section('h1','相簿建立')
@endif
@section('content')
<section>
    <div>
        <label>相簿封面</label>
        <div id="showCover">
            @isset($album)
            {!! $album->getCover() !!}
            @endisset
        </div>
        <input type="file" id="cover">
    </div>
    <div>
        <label>相簿名稱</label>
        <input type="text" id="albumName"
        @if (isset($album))
        value="{{ $album->name }}"
        @endif
        >
    </div>
    <div>
        <label>相簿說明</label>
        <input type="text" id="albumContent"
        @if (isset($album))
        value="{{ $album->content }}"
        @endif
        >
    </div>
    <div class="ali-r mt-3">
        @if (isset($album))
        <input type="hidden" id="id" value="{{ $album->id }}">
        <input type="hidden" id="action" value="update">
        <button class="btn btn-outline-primary" id="submit">更新</button>
        <button class="btn btn-outline-primary" id="delete">刪除</button>
        @else
        <button class="btn btn-outline-primary" id="submit">建立</button>
        @endif
        <button class="btn btn-outline-primary" id="reset">重寫</button>
    </div>
</section>
<div id="goBack"></div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/album/edit.js')}}"></script>
@endsection
