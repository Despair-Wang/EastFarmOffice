@extends('layouts.backend')
@section('title','文選編輯')
@section('customJs')
<script src="{{ asset('/js/postList.js') }}"></script>
@endsection
@section('content')
    <section>
        <div style="text-align: right">
            <button class="btn btn-info" id="multDeleteButton">整批刪除</button>
            <select id="type">
                <option value="all">全部顯示</option>
                <option value="done">已發布</option>
                <option value="undone">草稿</option>
            </select>
        </div>
        <div id="postListBox">
            @foreach ($posts as $post)
            <div class="editPostItem row" data-post-id="{{ $post->id}}">
                <div class="indexImage col-2">
                    <img src="{{ $post->image }}" alt="{{ $post->title }}">
                </div>
                <div class="col-9">
                    <h2>{{ $post->title }}</h2>
                    <p class="listPostCreateTime">{{ $post->getCreateDay() }}</p>
                    <div class="editArea">
                        @if($post->state == '2')
                        <a href="{{ '/post/' . $post->id  . '/edit/rewrite'}}">續寫</a>
                        @elseif($post->state == '1')
                        <a href="{{ '/post/' . $post->id  . '/edit/update'}}">編輯</a>
                        @endif
                        <a class="postDelete" href="">刪除</a>
                    </div>
                </div>
                <div class="col-1 multDelBox">
                    <input type="checkbox" class="multDelete">
                </div>
            </div>
            @endforeach
        </div>
        {!! $posts->links() !!}
    </section>
    <div id="createNew">
        <div>
            <div></div>
            <div></div>
        </div>
        <div></div>
    </div>
@endsection
@section('customJsBottom')
    <script>
        $(()=>{

        })
    </script>
@endsection
