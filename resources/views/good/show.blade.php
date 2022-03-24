@extends('layouts.basic')
@section('title',$good->name);
@section('content')
    <div class="row">
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
                    <button id="addCart" class="btn btn-info mt-5">加入購物車</button>
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
    <div id="typeSelect" data-good-id="{{ $good->id }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="w-100 text-right"><i id="closeWindow" class="fa fa-times curP scale2" aria-hidden="true"></i></div>
                </div>
                <div class="col-12">
                    <h2 class="h2 w-100 text-center">購買數量</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-4">款式</div>
                <div class="col-8">數量</div>
            </div>
            @foreach ($good->getTypes as $type)
            <div class="row selectItem" data-type="{{ $type->type }}">
                <div class="col-4">{{ $type->name }}</div>
                <div class="col-8">
                    <div class="orderNumber">
                        <div class="curP reduceNum">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </div>
                        <div><input type="number" class="order" value="0"></div>
                        <div class="curP addNum">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="row">
                <div class="col-12 text-right">
                    <button id="submit" class="btn btn-outline-primary">加入購物車</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('customJsBottom')
<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content'),
        }
    })
    var md = new MoveDom();
    let orderWindow = $('#typeSelect');
    orderWindow.hide();
    $(()=>{
        md.setBack('/o/good-list');
        $('#ctrlRight').children('i').click(function(){
            let now = $('#mediaBox').find('.onTop'),
                next = now.next();
            if(typeof(next[0]) == 'undefined'){
                next = $('#mediaBox > img:first-child');
            }
            next.addClass('onTop');
            now.removeClass('onTop');
        })

        $('#ctrlLeft').children('i').click(function(){
            let now = $('#mediaBox').find('.onTop'),
                next = now.prev();
            if(typeof(next[0]) == 'undefined'){
                next = $('#mediaBox > img:last-child');
            }
            next.addClass('onTop');
            now.removeClass('onTop');
        })

        $('#addCart').click(function(){
            $('body').addClass('hiddenScrollY');
            orderWindow.show();
        })

        $('.addNum').click(function(){
            let t = $(this).prev().find('input'),
            number = t.val();
            number++;
            t.val(number);
        })

        $('.reduceNum').click(function(){
            let t = $(this).next().find('input'),
            number = t.val()
            if(number <= 0){
                number = 0;
            }else{
                number--;
            }
            t.val(number);
        })

        $('#submit').click(function(){
            order();
        })

        $('#closeWindow').click(function(){
            closeWindow();
        })

        $('#cart').click(function(){
            location.href='/good/orderCheck';
        })
    })

    function order(){
        let id = $('#typeSelect').data('good-id'),
            orders = new Array(),
            types = $('.selectItem');

            types.each(function(){
                let type = $(this).data('type'),
                    number = $(this).find('.order').val();
                orders.push([type,number]);
            })

            $.ajax({
                url:'/api/good/addCart',
                type:'POST',
                data:{
                    id:id,
                    orders:orders,
                },success(data){
                    if(data['state'] == 1){
                        alert('商品已經加入購物車')
                        // closeWindow()
                        location.reload();
                    }else{
                        console.log(data['msg']);
                        console.log(data['data']);
                    }
                },error(data) {
                    console.log(data)
                }
            })
    }

    function closeWindow(){
        $('.order').each(function(){
            $(this).val(0);
        })
        orderWindow.hide();
        $('body').removeClass('hiddenScrollY');
    }
</script>
@endsection
