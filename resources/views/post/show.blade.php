@extends('layouts.basic')
@section('title',$post->title)
@section('h1',$post->title)
@section('content')
<div>
    <h3 class="h3">分類：</h3>
    <a class="h3" href="/o/post/category/{{ $post->category }}">《{{ $post->getCategory->name }}》</a>
</div>
<div id="postCover">
    <img src="{{ $post->image }}">
</div>
<div id="addedTag">
    @foreach ($tags as $tag)
        <div class="tagBox show" data-tag-id="{{ $tag['tagId'] }}">{{ $tag['name'] }}</div>
    @endforeach
    <div class="" id="post" data-postId="{{ $post->id }}">
        {!! $post->content !!}
    </div>
</div>
<div id="goBack"></div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/post/show.js')}}"></script>
@endsection
