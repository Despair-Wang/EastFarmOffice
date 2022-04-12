@extends('layouts.backend')
@section('title', '文章分類一覽')
@section('h1', '文章分類一覽')
@section('content')
    <section>
        <div style="text-align: right">
            <button class="btn btn-info" id="multDeleteButton">整批刪除</button>
        </div>
        <div id="postListBox">
            @foreach ($cates as $cate)
                <div class="editPostItem row" data-post-id="{{ $cate->id }}">
                    <div class="col-8">
                        <h2>{{ $cate->name }}</h2>
                    </div class="col-3">
                    <div class="editArea col-3">
                        <a class="btn btn-primary px-3 mr-2" href="{{ '/post/category/' . $cate->id . '/edit' }}">編輯</a>
                        <a class="postDelete btn btn-primary px-3" href="">刪除</a>
                    </div>
                    <div class="col-1 multDelBox">
                        <input type="checkbox" class="multDelete">
                    </div>
                </div>
            @endforeach
        </div>
        {!! $cates->links() !!}
    </section>
    <div id="createNew"></div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/post/category/list.js')}}"></script>
@endsection
