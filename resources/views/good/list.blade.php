@extends('layouts.basic')
@section('title','商品一覽')
@section('h1','商品一覽')
@section('content')
    <div class="row goodListBox">
        @forelse ($goods as $g)
            <div class="col-12 col-md-4 goodBox curP" data-id="{{ $g->serial }}" onclick="location.href = '/o/good/' + $(this).data('id');">
                <div>
                    <img src="{{ $g->cover }}" alt="">
                </div>
                <h3 class="h3 w-100 text-center">{{ $g->name }}</h3>
            </div>
        @empty
            暫無商品
        @endforelse
    </div>
    {!! $goods->links() !!}
@endsection
