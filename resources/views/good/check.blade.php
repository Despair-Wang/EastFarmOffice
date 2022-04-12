@extends('layouts.basic')
@section('title', '訂單確認')
@section('h1', '訂單確認')
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/jquery.twzipcode.min.js') }}"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-8 offset-md-2">
            <div id="orderCheckbox" class="">
                @if (Auth::check())
                    @if (Cache::has(Auth::id()))
                        @php
                            $count = 0;
                        @endphp
                        @foreach (Cache::get(Auth::id()) as $key => $item)
                            <div class="checkItem row mb-3" data-index="{{ $key }}">
                                <div class="col-12 col-md-4">{{ $item[2] . '-' . $item[3] }}</div>
                                <div class="col-6 col-md-3 price">$ {{ $item[5] }}</div>
                                <div class="col-5 col-md-2 number">X{{ $item[4] }}</div>
                                <div class="col-5 col-md-2 sum">$ </div>
                                <div class="col-1 col-md-1 text-right">
                                    <i class="fa fa-times curP delete" aria-hidden="true"></i>
                                </div>
                            </div>
                            @php
                                $count++;
                            @endphp
                        @endforeach
                        <div class="row">
                            <div class="col-12 text-right">
                                <h4 id="total" class="h4">Total </h4>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-6">
                                <label for="">收件人</label>
                                <input id="name" type="text" class="w-100">
                            </div>
                            <div class="col-6">
                                <label for="">連絡電話</label>
                                <input id="tel" type="tel" class="w-100">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-12">
                                <label for="">付款方式(含運費)</label><br>
                                @php
                                    $count = 0;
                                @endphp
                                @forelse ($payments as $p)
                                @if ($count == 0)
                                <input id="pay{{ $p->id }}" class="curP" type="radio" name="payWay" checked="checked" value="{{ $p->id }}">
                                @else
                                <input id="pay{{ $p->id }}" class="ml-3 curP" type="radio" name="payWay" value="{{ $p->id }}">
                                @endif
                                <label class="curP" for="pay{{ $p->id }}">{{ $p->name }}<span class="freight">${{ $p->price }}</span></label>
                                @php
                                    $count++;
                                @endphp
                                @empty
                                @endforelse
                                {{-- <input class="ml-3" type="radio" name="payWay" id="comeShop" value="shop"><label class="curP" for="comeShop">店面取貨付款<span class="freight">$0</span></label> --}}
                            </div>
                        </div>
                        <div id="addressBox" class="row my-2">
                            <div class="col-12">
                                <label for="">送貨地址</label>
                            </div>
                            <div class="col-12 mb-2">
                                <div id="twzipcode"></div>
                            </div>
                            <div class="col-12">
                                <input id="address" type="text" class="w-100">
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-12">
                                <label>備註</label>
                                <div id="remark" contenteditable="true" class="textarea" placeholder="請輸入備註(可空白)"></div>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-6 pr-1">
                                <button id="buy" class="btn btn-primary w-100">結帳</button>
                            </div>
                            <div class="col-6 pl-1">
                                <button id="continue" class="btn btn-primary w-100">繼續購買</button>
                            </div>
                        </div>
                    </div>
                @else
                    購物車是空的
                    {{-- <input type="hidden" id="nothing" value="true"> --}}
                @endif
            @endif
        </div>
    </div>
    </div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/good/check.js')}}"></script>
@endsection
