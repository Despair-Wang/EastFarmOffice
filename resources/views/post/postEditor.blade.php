@extends('layouts.backend')
@section('title', 'POST EDITOR')
@section('customJs')
    <script src='https://cdn.jsdelivr.net/npm/wangeditor@latest/dist/wangEditor.min.js'></script>
@endsection
@section('content')
    <section>
        <label>文章標題：</label>
        <input type="text" id="title" @if (isset($action)) value = "{{ $post->title }}" @endif>
    </section>
    <section id="postEditArea2" class="row">
        <div class="col-12 col-md-4">
            <label>文章形象圖</label>
            <div id="uploadArea">
                <img id="indexImage" src="
                    @if (isset($action)) {{ $post->image }} @endif
                    " alt="">
            </div>
            <input type="file" id="imgUpload">
        </div>
        <div class="col-12 col-md-8">
            <section>
                <div>
                    <label>文章分類：</label>
                    <select name="" id="category">
                        <option>請選擇分類</option>
                        @forelse ($categories as $cate)
                        <option value="{{ $cate['id'] }}" @if (isset($action) && $cate['id'] == $post->category) selected @endif>
                            {{ $cate['name'] }}</option>
                            @empty
                            <option>No Anything</option>
                            @endforelse
                    </select>
                </div>
            </section>
            <section>
                <div>
                    <label>文章標籤：</label>
                    <select name="" id="tag">
                        <option value="-">請選擇新增的標籤</option>
                        @forelse ($tags as $tag)
                        <option value="{{ $tag['id'] }}">{{ $tag['name'] }}</option>
                    @empty
                    <option>No Anything</option>
                    @endforelse
                    </select>
                {{-- <button id="addTag">新增</button> --}}
                    <div id="addedTag">
                        @if (isset($action))
                            @if (isset($tagForPost) && count($tagForPost) != 0)
                                @foreach ($tagForPost as $tag)
                                    <div class="tagBox">
                                        <div class="tag" data-tag-id="{{ $tag['id'] }}">{{ $tag['name'] }}</div>
                                        <a class="removeTag">X</a>
                                    </div>
                                @endforeach
                            @else
                                @if (!is_null(Cache::get(Auth::id() . 'tag')))
                                    @foreach (Cache::get(Auth::id() . 'tag') as $tag)
                                        <div class="tagBox">
                                            <div class="tag" data-tag-id="{{ $tag['id'] }}">{{ $tag['name'] }}</div>
                                            <a class="removeTag">X</a>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </section>
    <section>
        <div id="mainInput"></div>
    </section>
    <section>
        <input type="hidden" id="version" value="
            @if (isset($action)) {{ $post->version }}
        @else
            0 @endif
            ">
        <div id="activeArea">
            @if (isset($action))
                <div id="action" data-action="{{ $action }}" data-post-id="{{ $post->id }}"></div>
            @else
                <button class="btn btn-outline-primary" id="draft">存成草稿</button>
            @endif
            <button class="btn btn-outline-primary" id="submit">送出</button>
            <button class="btn btn-outline-primary" id="reset">清空重寫</button>
        </div>
    </section>
    <div id="goBack"></div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/post/edit.js')}}"></script>
@endsection
