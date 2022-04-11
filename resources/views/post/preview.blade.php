@extends('layouts.basic')
@section('title', $post->title)
@section('h1', $post->title)
@section('content')
    <div>
        <h3 class="h3">分類：</h3>
        <a class="h3">《{{ $post->getCategory->name }}》</a>
    </div>
    <div id="postCover">
        <img style="width:300px" src="{{ $post->image }}">
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
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/post/preview.js')}}"
@endsection
@section('customJsBottom')
    {{-- <script>
        $(() => {
            let id = $('#post').data('postid');
            $('#rewrite').click(function() {
                console.log('click');
                location.href = `/post/${id}/edit/rewrite`;
            })

            $('.tagBox.show').click(function() {
                let d = $(this).data('tag-id');
                location.href = '/o/post/tag/' + d;
            })

            $('#complete').click(function() {
                $.ajax({
                    url: `/api/post/${id}/beComplete`,
                    type: 'get',
                    success(result) {
                        if (result['state'] == '1') {
                            let post = result['data']['id'];
                            alert('發佈成功');
                            location.href = '/post/list';
                        } else {
                            alert('發佈失敗');
                            console.log(result['data']);
                        }
                    }
                })
            })
        })
    </script> --}}
@endsection
