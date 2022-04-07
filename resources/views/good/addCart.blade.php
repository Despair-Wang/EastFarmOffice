@extends('layouts.basic')
@section('title','加入購物車')
@section('h1','加入購物車')
@section('content')
<div id="typeSelect" data-good-id="{{ $good->id }} " data-good-serial="{{ $good->serial }}">
    <div>
        <div class="row">
            <div class="col-6 col-md-4">
                <img src="{{$good->cover}}" alt="">
            </div>
            <div class="col-6 col-md-8">
                <h4 class="h4">{{ $good->name }}</h4>
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
<div id="goBack"></div>
@endsection
@section('customJsBottom')
<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content'),
        }
    })
    var md = new MoveDom(),
        id = $('#typeSelect').data('good-id'),
        serial = $('#typeSelect').data('good-serial');
    $(()=>{
        md.setBack('/o/good-list');

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

        $('#cart').click(function(){
            location.href='/good/orderCheck';
        })
    })

    function order(){
        let orders = new Array(),
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
                        location.href="/o/good-list";
                    }else{
                        console.log(data['msg']);
                        console.log(data['data']);
                    }
                },error(data) {
                    console.log(data)
                }
            })
    }
</script>
@endsection
