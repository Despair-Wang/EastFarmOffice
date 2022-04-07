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
@section('customJsBottom')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
            }
        })
        $(() => {
            // var total = 0;
            // $('.checkItem').each(function(){
            //     let t = $(this),
            //     number = parseInt((t.find('.number').html()).replace('X','')),
            //     price = parseInt((t.find('.price').html()).replace('$ ',''));
            //     temp = number * price;
            //     total += temp;
            //     t.find('.sum').html('$ ' + temp);
            // })
            // $('#total').html('Total ' + total);
            $('#twzipcode').twzipcode();

            init();

            $('.delete').click(function() {
                let t = $(this).parents('.checkItem'),
                    index = t.data('index');
                $.ajax({
                    url: '/api/good/cartChange',
                    type: 'POST',
                    data: {
                        index: index,
                    },
                    success(data) {
                        if (data['state'] == 1) {
                            if(data['data'] == 'reload'){
                                location.reload();
                            }else{
                                t.remove();
                                init();
                            }
                        } else {
                            console.log(data['msg']);
                            console.log(data['data']);
                        }
                    },
                    error(data) {
                        console.log(data)
                    }
                })
            })

            $('input[name="payWay"]').on('change', function(){
                if($(this).val() == '2'){
                    $('#addressBox').hide();
                }else{
                    $('#addressBox').show();
                };
            })

            $('#buy').click(function() {
                let name = $('#name').val(),
                    tel = $('#tel').val(),
                    zipcode = $('[name="zipcode"]').val(),
                    city = $('select[name="county"]').find(':selected').val(),
                    dist = $('select[name="district"]').find(':selected').val(),
                    address = $('#address').val(),
                    payWay = $('[name="payWay"]:checked'),
                    pay = payWay.val(),
                    freight = (payWay.next().find('.freight').html()).replace('$',''),
                    remark = inputFormat($('#remark').html()),
                    nothing = $('#nothing').val();

                if(nothing != 'true'){
                    $.ajax({
                        url:'/api/good/order',
                        type: 'POST',
                        data:{
                            name:name,
                            tel:tel,
                            zipcode:zipcode,
                            city:city,
                            dist:dist,
                            address:address,
                            pay:pay,
                            freight:freight,
                            remark:remark,
                        },success(data) {
                            if(data['state'] == 1){
                                let serial = data['data'];
                                location.href=`/order/${serial}/complete`;
                            }else{
                                let msg = '訂購失敗' + data['msg'];
                                switch(data['msg']){
                                    case 'NO_NAME':
                                        msg = '請輸入收件者姓名';
                                        break;
                                    case 'NO_TEL':
                                        msg = '請輸入收件者電話';
                                        break;
                                    case 'NO_ZIPCODE':
                                        msg = '請輸入郵遞區號';
                                        break;
                                    case 'NO_ADDRESS':
                                    case 'NO_CITY':
                                    case 'NO_DISTRICT':
                                        msg = '請輸入完整的收件地址';
                                        break;
                                }
                                alert(msg);
                                console.log(data['data']);
                            }
                        },error(data) {
                            console.log(data)
                        }
                    })
                }else{
                    alert('購物車是空的，請先去購物');
                }
            })

            $('#remark').bind('paste',function(e){
                e.preventDefault();
                let old = $(this).html();
                let t = e.originalEvent.clipboardData.getData('text');
                $(this).html(old + t);
            })

            $('#continue').click(function() {
                location.href = '/o/good-list';
            })

            function init() {
                var total = 0,
                    count = 0;
                $('.checkItem').each(function() {
                    let t = $(this),
                        number = parseInt((t.find('.number').html()).replace('X', '')),
                        price = parseInt((t.find('.price').html()).replace('$ ', ''));
                    temp = number * price;
                    total += temp;
                    // t[0].setAttribute('data-index',count);
                    t.find('.sum').html('$ ' + temp);
                    count++;
                })
                $('#total').html('Total ' + total);

                // $('.delete').unbind('click');
                // $('.delete').click(function(){
                // let t = $(this).parents('.checkItem'),
                //     index = t.data('index');
                //     $.ajax({
                //         url: '/api/good/cartChange',
                //         type:'POST',
                //         data:{
                //             index:index,
                //         },success(data){
                //             if(data['state'] == 1){
                //                 t.remove();
                //                 init();
                //             }else{
                //                 console.log(data['msg']);
                //                 console.log(data['data']);
                //             }
                //         },error(data) {
                //             console.log(data)
                //         }
                //     })
                // })
            }

            function inputFormat(content){
                let start = /<div>/g,
                    end = /<\/div>/g;
                content = content.replace('<div>','<br>');
                content = content.replace(/<div>/g,'');
                content = content.replace(/<\/div>/g,'<br>');
                return content;
            }
        })
    </script>
@endsection
