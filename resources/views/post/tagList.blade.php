@extends('layouts.backend')
@section('title', '文章標籤一覽')
@section('h1', '文章標籤一覽')
@section('content')
    <section>
        <div style="text-align: right">
            <button class="btn btn-info" id="multDeleteButton">整批刪除</button>
        </div>
        <div id="postListBox">
            @foreach ($tags as $tag)
                <div class="editPostItem row" data-post-id="{{ $tag->id }}">
                    <div class="col-8">
                        <h2>{{ $tag->name }}</h2>
                    </div>
                    <div class="editArea col-3">
                        <a class="btn btn-primary px-3 mr-2" href="{{ '/post/tag/' . $tag->id . '/edit' }}">編輯</a>
                        <a class="postDelete btn btn-primary px-3" href="">刪除</a>
                    </div>
                    <div class="col-1 multDelBox">
                        <input type="checkbox" class="multDelete">
                    </div>
                </div>
            @endforeach
        </div>
        {!! $tags->links() !!}
    </section>
    <div id="createNew"></div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/post/tag/list.js')}}"></script>
@endsection
