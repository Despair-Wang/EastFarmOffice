@extends('layouts.basic')
@section('title','茶花百科項目一覽')
@section('h1','茶花百科項目一覽')
@section('content')
<div id="filterBox">
    @forelse ($types as $type)
        <div class="filter" data-id="{{ $type->id }}">
            <h4 class="h4 d-inline-block pr-3">{{ $type->name }}</h4>
            <input id="type{{ $type->id }}" class="d-none" type="radio" name="{{ $type->id }}" value="all-{{ $type->id }}" checked="true">
            <label class="btn btn-outline-success filterBtn" for="type{{ $type->id }}">所有</label>
            @forelse ($type->getTags as $tag)
                <input id="tag{{ $tag->id }}" class="d-none" type="radio" name="{{ $type->id }}" value="{{ $tag->id }}">
                <label class="btn btn-outline-success filterBtn"
                for="tag{{ $tag->id }}">{{ $tag->name }}</label>
            @empty
            @endforelse
        </div>
    @empty
    @endforelse
    <div class="row">
        @forelse ($pedia as $p)
        <div class="col-6 p-2 pediaItem curP" data-name="{{ $p->name }}">
            <div class="row">
                <div class="col-5">
                    <img src="{{ $p->image }}" class="img-fluid">
                </div>
                <div class="col-7">
                    <h5 class="h5 border-primary pl-2">{{ $p->name }}</h5>
                    <p>分類：{{$p->getCategoryName()}}</p>
                    <div>
                        @forelse ($types as $type)
                            <div>
                                <p>{{ $type->name }}：
                                @forelse ($type->getTagForItem($p->id) as $tag)
                                    {{ $tag->name }} &nbsp;
                                @empty
                                @endforelse
                                </p>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @empty
        @endforelse
    </div>
</div>
<form action="/o/pedia-list/filter" method="post" id="filterForm">
    @csrf
    <input type="hidden" name="filter" value="{{ $filter }}">
</form>
@endsection
@section('customJsBottom')
<script type="text/javascript" src="{{ asset('js/pedia/list.js') }}"></script>
@endsection
