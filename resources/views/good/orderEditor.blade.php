@extends('layouts.backend')
@section('title','訂單修改-編號:' . $order->serial)
@section('h1','訂單修改-編號:' . $order->serial)
@section('content')
<div id="orderDetailBox" class="row" data-serial="{{ $order->serial }}">
    <div class="col-12">
        <h4 class="h4">
            訂單狀態
        </h4>
    </div>
    <div class="col-12">
        <select id="state">
            @forelse ($status as $s)
            @if ($order->state == $s->stateId)
                <option value="{{ $s->stateId }}" selected >{{ $s->name }}</option>
            @else
                <option value="{{ $s->stateId }}">{{ $s->name }}</option>
            @endif
            @empty
                <option value="-">NO DATA</option>
            @endforelse
        </select>
    </div>
    <div class="col-12">
        <h4 class="h4">
            訂購人
        </h4>
    </div>
    <div class="col-12">
        <input id="name" type="text" value="{{ $order->name }}">
    </div>
    <div class="col-12">
        <h4 class="h4">
            電話
        </h4>
    </div>
    <div class="col-12">
        <input id="tel" type="text" value="{{ $order->tel }}">
    </div>
    <div class="col-12">
        <h4 class="h4">
            配送地址
        </h4>
    </div>
    <div class="col-12">
        <input id="zipcode" type="text" value="{{ $order->zipcode }}">
        <input id="address" type="text" value="{{ $order->address }}">
    </div>
    <div class="col-12">
        <h4 class="h4">
            下單時間
        </h4>
    </div>
    <div class="col-12">
        <h4 class="h4">
            {{ $order->getCreateTime() }}
        </h4>
    </div>
    <div class="col-12">
        <h4 class="h4">
            訂購內容
        </h4>
    </div>
    <div class="col-11 offset-1">
        @foreach ($order->getDetails as $d)
        <div class="typeBox row" id="{{ $d->id }}">
            <div class="col-4">
                <h5 class="h5">{{ $d->getName() }}</h5>
                <h6 class="h6">{{ $d->getType() }}</h6>
            </div>
            <div class="col-4">$
                <input type="number" class="amount" value="{{ $d->amount }}">
            </div>
            <div class="col-3">x
                <input type="number" class="quantity" value="{{ $d->quantity }}">
            </div>
            <div class="col-1">
                <i class="fa fa-times float-right typeDel curP" aria-hidden="true"></i>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-12">
        <h4 class="h4">
            運費
        </h4>
    </div>
    <div class="col-12">$
        <input type="number" id="freight" value="{{ $order->freight }}">
    </div>
    <div class="col-12">
        <h4 class="h4">
            總金額
        </h4>
    </div>
    <div class="col-12">
        <input type="number" id="total" value="{{ $order->total }}">
        <button id="sum" class="btn btn-primary w-100">重新計算總金額</button>
    </div>
    <div class="col-12">
        <h4 class="h4">
            付款方式
        </h4>
    </div>
    <div class="col-12">
        <select id="payment">
            @foreach ($payments as $p)
            @if ($order->pay == $p->id )
                <option value="{{ $p->id }}" selected>{{ $p->name }}</option>
            @else
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endif
            @endforeach
        </select>
        </h4>
    </div>
</div>
<div class="row">
    <div class="col-6 p-2">
        <button id="submit" class="btn btn-primary w-100">更改訂單</button>
    </div>
    <div class="col-6 p-2">
        <button id="reset" class="btn btn-primary w-100">恢復</button>
    </div>
</div>
<div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script>
        var deleteType = new Array(),
            serial = $('#orderDetailBox').data('serial'),
            md = new MoveDom();
        $(()=>{
            md.setBack('/good/order/list');
            $('.typeDel').click(function(){
                let t = $(this).parents('.typeBox'),
                    id = t.attr('id');
                deleteType.push(id);
                t.remove();
            })

            $('#sum').click(function(){
                let total = 0;
                $('.typeBox').each(function(){
                    let t = $(this),
                        a = parseInt(t.find('.amount').val()),
                        q = parseInt(t.find('.quantity').val());
                    total += a * q;
                })
                $('#total').val(total);
            })

            $('#submit').click(function(){
                let state = $('#state').find(':selected').val(),
                    name = $('#name').val(),
                    address = $('#address').val(),
                    types = new Array(),
                    freight = $('#freight').val(),
                    total = $('#total').val(),
                    payment = $('#payment').find(':selected').val();
                $('.typeBox').each(function(){
                    let id = $(this).attr('id'),
                        a = $(this).find('.amount').val(),
                        q = $(this).find('.quantity').val();
                    types.push([id,a,q]);
                })

                $.ajax({
                    url:`/api/good/order/${serial}/edit`,
                    type:'POST',
                    data:{
                        state:state,
                        name:name,
                        address:address,
                        types:types,
                        freight:freight,
                        total:total,
                        pay:payment,
                        deleteType:deleteType,
                    },success(data){
                        if(data['state'] == 1){
                            alert('訂單修改完成');
                            location.href=`/good/order/${serial}/`;
                        }else{
                            console.log(data['msg'])
                            console.log(data['data'])
                        }
                    },error(data){
                        console.log(data)
                    }
                })

            })

            $('#reset').click(function(){
                location.reload();
            })
        })
    </script>
@endsection
