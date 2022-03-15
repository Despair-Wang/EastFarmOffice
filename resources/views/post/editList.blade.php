@extends('layouts.backend')
@section('title','文選編輯')
@section('customJs')
<script src="{{ asset('/js/postList.js') }}"></script>
@endsection
@section('content')
    <section>
        <div id="filterBox" style="text-align: right">
            <div>
                <select id="type">
                    <option value="all">全部顯示</option>
                    <option value="done">已發布</option>
                    <option value="undone">草稿</option>
                </select>
            </div>
            <div>
                <button class="btn btn-info" id="multDeleteButton">整批刪除</button>
            </div>
        </div>
        <div id="postListBox">
            @foreach ($posts as $post)
            <div class="editPostItem row align-items-center @if ($post->state == '2')rewriteItem @endif" data-post-id="{{ $post->id}}">
                <div class="indexImage col-2">
                    <img src="{{ $post->image }}" alt="{{ $post->title }}">
                </div>
                <div class="col-9">
                    <h2>{{ $post->title }}</h2>
                    <p class="listPostCreateTime">{{ $post->getCreateDay() }}</p>
                    <div class="editArea">
                        @if($post->state == '2')
                        <a class="btn btn-primary px-5 mr-3" href="{{ '/post/' . $post->id  . '/edit/rewrite'}}">續寫</a>
                        @elseif($post->state == '1')
                        <a class="btn btn-primary px-5 mr-3" href="{{ '/post/' . $post->id  . '/edit/update'}}">編輯</a>
                        @endif
                        <a class="btn btn-primary px-5 postDelete" href="">刪除</a>
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
            let url = location.href,
            star = url.indexOf('list/'),
            haveType = url.indexOf('type')
            type = '';

            type = url.split('list/')[1];
            if(star > 0){
                let end = type.indexOf('?');
                if(end < 0){
                    end = type.length;
                }
                type = type.slice(0, end);
            }
            console.log(type);
            $('#type').find(`[value="${type}"]`).attr('selected',true);

            $('#type').change(function(){
                let target = $(this).val();
                location.href='/post/list/' + target;
            })
        })
    </script>
@endsection
