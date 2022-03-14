@extends('layouts.basic')
@section('title',$post->title)
@section('h1',$post->title)
@section('content')
<div>
    <a href="/o/post/category/{{ $post->category }}">{{ $post->getCategory->name }}</a>
</div>
<img src="{{ $post->image }}">
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
@section('customJsBottom')
<script>
    $(()=>{
        var md = new MoveDom();
        md.setBack('/o/post-list');
        let id = $('#post').data('postid');
        $('#rewrite').click(function(){
            console.log('click');
            location.href=`/post/${id}/edit/rewrite`;
        })

        $('.tagBox.show').click(function(){
            let d = $(this).data('tag-id');
            location.href = '/o/post/tag/' + d;
        })
    })
</script>
@endsection
