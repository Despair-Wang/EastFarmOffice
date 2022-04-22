@extends('layouts.basic')
@section('title', '文章列表')
@section('h1', '茶花文選')
@section('content')
    @if (isset($cate))
        <p class="searchResult">分類為{{ $cate->name }}的文章，一共找到{{ $count }}筆</p>
    @elseif(isset($targetTag))
        <p class="searchResult">分類為{{ $targetTag->name }}的文章，一共找到{{ $count }}筆</p>
    @endif
    <div id="pageLimit" class="ali-r">
        <label>文章顯示數量:</label>
        <a href="/o/post-list">6</a>
        <a href="/o/post-list?limit=9">9</a>
        <a href="/o/post-list?limit=12">12</a>
        <a href="/o/post-list?limit=15">15</a>
    </div>
    <div class="row">
        @foreach ($posts as $post)
        @if(isset($targetTag))
            @php
            $post = $post->getPost
            @endphp
        @endif
            <div class="listPostItem col-lg-4 col-12">
                <div onclick="location.href='{{ '/o/post/' . $post->id }}'">
                    <h3 class="h3">{{ $post->title }}</h3>
                    <div class="postImage">
                        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="img-fluid">
                    </div>
                    <div class="listPostTagBox">
                        <div>
                            @foreach ($post->getTags as $tag)
                                <div class="listPostTag" data-tagId="{{ $tag->getTag->id }}">{{ $tag->getTag->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="listPostCreateTime">{{ $post->getCreateDay() }}</p>
                    <div class="ali-r">
                        <p>(繼續閱讀)</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {!! $posts->links() !!}
@endsection
