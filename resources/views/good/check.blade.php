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
                    @if (Cache::has('good' . Auth::id()))
                        @php
                            $count = 0;
                        @endphp
                        @foreach (Cache::get('good' . Auth::id()) as $key => $item)
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
                        <div class="row">
                            <div class="col-12">
                                <div class="float-right">
                                    <input class="curP" id="useUserInfo" type="checkBox">
                                    <label for="useUserInfo" class="curP">與會員資料相同</label>
                                </div>
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
                        <div id="addressBox" class="row my-2">
                            <div class="col-12">
                                <label for="">送貨地址</label>
                            </div>
                            <div class="col-12" id="addressShow">
                                <input type="hidden" id="zipcodeInput">
                                <input type="hidden" id="addressInput">
                                <h6 class="h6"></h6>
                            </div>
                            <div class="col-12">
                                <button id="selectAddress" class="btn btn-primary float-right">選取送貨地址</button>
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
                        <div class="row my-2">
                            <div class="col-12">
                                <label>發票形式</label>
                            </div>
                            <div class="col-12">
                                <input id="twoPart" class="curP" type="radio" name="invoiceType" checked="checked" value="twoPart">
                                <label class="curP" for="twoPart">二聯式發票</label>
                                <input id="triplePart" class="curP" type="radio" name="invoiceType" value="triplePart">
                                <label class="curP" for="triplePart">三聯式發票</label>
                                <input type="text" id="taxNumber" placeholder="請輸入統編" maxlength="8">
                                <input id="donate" class="curP" type="radio" name="invoiceType" value="donate">
                                <label class="curP" for="donate">捐贈發票</label>
                            </div>
                        </div>
                    </div>
                    <div id="sendInvoice" class="row my-2">
                        <div class="col-12">
                            <label>發票寄送方式</label>
                        </div>
                        <div class="col-12">
                            <input type="radio" name="sendType" id="withGood" value="withGood" checked>
                            <label for="withGood">隨貨寄送發票</label>
                            <input type="radio" name="sendType" id="another" value="another">
                            <label for="another">指定寄送地點</label>
                            <div id="anotherAddress">
                                <input type="hidden" id="subZipcodeInput">
                                <input type="hidden" id="subAddressInput">
                                <h6 id="subAddress" class="h6"></h6>
                                <button id="selectSubAddress" class="btn btn-primary">選取寄送地址</button>
                            </div>
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
    <div id="commonlyUsed" data-target="null">
        <div>
            <div class="container">
                <div class="text-right">
                    <i id="closeAddress" class="fa fa-times curP" aria-hidden="true"></i>
                </div>
                <h3 class="h3 text-center">選擇常用地址</h3>
                <div class="row">
                    <div class="col-12">
                        <button id="addAddress" class="btn btn-primary my-3 float-right">增加新地址</button>
                    </div>
                </div>
                <div id="addressKeyIn" class="row">
                    <div class="col-12 mb-2">
                        <div id="twzipcode"></div>
                    </div>
                    <div class="col-12">
                        <input id="address" type="text" class="w-100">
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button id="addressSubmit" class="btn btn-primary mt-3">新增地址</button>
                    </div>
                </div>
                <div id="addressList" class="row my-3">
                </div>
                <button id="useAddress" class="btn btn-primary w-100 my-3">使用所選地址</button>
            </div>
        </div>
    </div>
@endsection
@section('customJsBottom')
    <script type="text/javascript" src="{{ asset('js/good/check.js')}}"></script>
@endsection
