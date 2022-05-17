@extends('layouts.backend')
@section('title', '百科項目一覽')
@section('t1', '百科項目一覽')
@section('content')
    <div class="row">
        @forelse ($items as $item)
            <div class="col-12 col-md-3 p-3">
                <div class="itemIcon curP" onclick="location.href='{{ url('/pedia/' . $item->id . '/preview') }}'">
                    <h4 class="h4 text-center">{{ $item->name }}</h4>
                    <img src="{{ url($item->image) }}" alt="">
                </div>
            </div>
        @empty

        @endforelse
    </div>
@endsection
