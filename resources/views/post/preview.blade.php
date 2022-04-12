@extends('layouts.basic')
@section('title', $post->title)
@section('h1', $post->title)
@section('content')
    <div>
        <h3 class="h3">分類：</h3>
        <a class="h3">《{{ $post->getCategory->name }}》</a>
    </div>
    <div id="postCover">
        <img src="{{ $post->image }}">
    </div>
    <div id="addedTag">
        @foreach ($tags as $tag)
            <div class="tagBox show" data-tag-id="{{ $tag['id'] }}">{{ $tag['name'] }}</div>
        @endforeach
    </div>
    <div class="" id="post" data-postId="{{ $post->id }}">
        {!! $post->content !!}
    </div>
    <button class="btn btn-outline-primary" id="rewrite">重寫</button>
    <button class="btn btn-outline-primary" id="complete">發佈</button>
@endsection
@section('customJsBottom')
    <script type="text/javascript" src="{{ asset('js/post/preview.js')}}"></script>
@endsection
