@extends('layouts.backend')
@section('title', '百科屬性一覽')
@section('h1', '百科屬性一覽')
@section('content')
    <section>
        <div style="text-align: right">
            <button class="btn btn-info" id="multDeleteButton">整批刪除</button>
        </div>
        <div id="postListBox">
            <div class="row">
                <div class="col-4">屬姓名</div>
                <div class="col-4">所屬分類</div>
                <div class="col-3">操作</div>
            </div>
            @forelse ($tags as $tag)
                <div class="editPostItem row" data-post-id="{{ $tag->id }}">
                    <div class="col-4">
                        <h2>{{ $tag->name }}</h2>
                    </div>
                    <div class="col-4">
                        <h2>{{ $tag->getType->name }}</h2>
                    </div>
                    <div class="editArea col-3">
                        <a class="btn btn-primary px-3 mr-2" href="{{ '/pedia/tag/' . $tag->id . '/edit' }}">編輯</a>
                        <a class="postDelete btn btn-primary px-3" href="">刪除</a>
                    </div>
                    <div class="col-1 multDelBox">
                        <input type="checkbox" class="multDelete">
                    </div>
                </div>
            @empty
            <p>尚無任何百科屬性</p>
            @endforelse
        </div>
        {!! $tags->links() !!}
    </section>
    <div id="createNew"></div>
@endsection
@section('customJsBottom')
    <script type="text/javascript" src="{{ asset('js/pedia/tag/list.js')}}"></script>
@endsection
