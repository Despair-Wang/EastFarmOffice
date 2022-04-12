@extends('layouts.basic')
@section('title',$good->name);
@section('content')
    <div class="row">
        <div class="col-12 categoryBox">
            <a href="{{ url('/o/good-list/') . '/' . $good->category }}">{{ $good->getCategory->name }}</a>
        </div>
        <div class="col-12 col-md-4">
            <div id="mediaArea">
                <div id="mediaBox">
                    <img class="onTop" src="{{ $good->cover }}">
                    @foreach ($good->gallery as $gallery)
                    <img src="{{ $gallery }}">
                    @endforeach
                </div>
                <div id="ctrlRight">
                    <i class="fa fa-chevron-right curP" aria-hidden="true"></i>
                </div>
                <div id="ctrlLeft">
                    <i class="fa fa-chevron-left curP" aria-hidden="true"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div>
                <h2 class="h2">{{ $good->name }}</h2>
                <div>
                    @foreach ($good->getTypes as $type)
                    <div class="typeBox row">
                        <div class="col-5">{{ $type->name }}</div>
                        <div class="col-5">{{ $type->price }} 元</div>
                        <div class="col-2">庫存 {{ $type->getStock() }}</div>
                        <div class="col-12"><h6 class="h6">{{ $type->description }}</h6></div>
                    </div>
                    @endforeach
                </div>
                <div class="text-right">
                    <form method="post" action="{{ route('addCart') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $good->id }}">
                        <button type="submit" class="btn btn-outline-primary mt-5">加入購物車</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 p-5">
            <p>{!! $good->caption !!}</p]>
        </div>
    </div>
    <div id="goBack"></div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/good/show.js')}}"></script>
@endsection
