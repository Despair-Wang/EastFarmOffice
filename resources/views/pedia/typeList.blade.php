@extends('layouts.backend')
@section('title', '百科屬性分類一覽')
@section('h1', '百科屬性分類一覽')
@section('content')
    <section>
        <div style="text-align: right">
            <button class="btn btn-info" id="multDeleteButton">整批刪除</button>
        </div>
        <div id="postListBox">
            @forelse ($types as $type)
                <div class="editPostItem row" data-post-id="{{ $type->id }}">
                    <div class="col-8">
                        <h2>{{ $type->name }}</h2>
                    </div>
                    <div class="editArea col-3">
                        <a class="btn btn-primary px-3 mr-2" href="{{ '/pedia/type/' . $type->id . '/edit' }}">編輯</a>
                        @if($type->state == 1)
                        <a class="btn btn-primary px-3 mr-2 freeze">
                        凍結</a>
                        @elseif($type->state == 2)
                        <a class="btn btn-primary px-3 mr-2 recover">啟用</a>
                        @endif
                        <a class="postDelete btn btn-primary px-3">刪除</a>
                    </div>
                    <div class="col-1 multDelBox">
                        <input type="checkbox" class="multDelete">
                    </div>
                </div>
            @empty
            <p>尚無任何百科屬性分類</p>
            @endforelse
        </div>
        {!! $types->links() !!}
    </section>
    <div id="createNew"></div>
@endsection
@section('customJsBottom')
    <script type="text/javascript" src="{{ asset('js/pedia/type/list.js')}}"></script>
@endsection
