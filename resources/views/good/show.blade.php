@extends('layouts.basic')
@section('title',$good->name)
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
                <div class="d-flex align-items-center">
                    <h2 class="h2">{{ $good->name }}
                        @if($good->checkFavorite())
                        <i id="favorite" class="curP fa fa-heart ml-2 text-danger" aria-hidden="true"></i>
                        @else
                        <i id="favorite" class="curP fa fa-heart-o ml-2" aria-hidden="true"></i>
                        @endif
                    </h2>
                    <span class="ml-2" id="favoriteNotice"></span>
                </div>
                <div>
                    @php
                        $total = count($good->getTypes);
                        $count = 0;
                    @endphp
                    @foreach ($good->getTypes as $type)
                    <div class="typeBox row">
                        <div class="col-5">{{ $type->name }}</div>
                        <div class="col-5">{{ $type->price }} 元</div>
                        @if($type->getStock() == 0)
                        @php
                            $count++;
                        @endphp
                        <div class="col-2">已售完</div>
                        @else
                        <div class="col-2">庫存 {{ $type->getStock() }}</div>
                        @endif
                        <div class="col-12"><h6 class="h6">{{ $type->description }}</h6></div>
                    </div>
                    @endforeach
                </div>
                <div class="text-right">
                    <form method="post" action="{{ route('addCart') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $good->id }}">
                        @if($total == $count)
                        <h4 class="h4 bg-danger text-light p-1 pr-4 my-3">已全數售完，無法購買</h4>
                        @else
                        <button type="submit" class="btn btn-outline-primary mt-5">加入購物車</button>
                        @endif
                        @if($count > 0)
                        <button id="restockNotice" class="btn btn-info mt-5">當商品有補貨時通知我</button>
                        @endif
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
@section('customJsBottom')
    <script type="text/javascript" src="{{ asset('js/good/show.js')}}"></script>
@endsection
