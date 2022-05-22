@extends('layouts.backend')
@section('title', $type . '百科項目一覽')
@section('t1', $type . '百科項目一覽')
@section('content')
@if ($type == '啟用')
<a class="btn btn-info" href="{{ url('/pedia/deleteList') }}">前往刪除項目</a>
@else
<a class="btn btn-info" href="{{ url('/pedia/list') }}">前往啟用項目</a>
@endif
    <div class="row">
        @forelse ($items as $item)
            <div class="col-12 col-md-3 p-3">
                <div class="itemIcon curP" onclick="location.href='{{ url('/pedia/' . $item->id . '/preview') }}'">
                    <h4 class="h4 text-center">{{ $item->name }}</h4>
                    <img src="{{ url($item->image) }}" alt="">
                </div>
            </div>
        @empty
        <p>尚無任何項目</p>
        @endforelse
    </div>
    <div>{!! $items->links() !!}</div>
    <div id="createNew"></div>
@endsection
@section('customJsBottom')
<script>
    $(()=>{
        var md = new MoveDom();
        md.setNew('/pedia/edit');
    })
    </script>
@endsection
