@extends('layouts.basic')
@section('title','標題')
@section('content')
<style>

    .picframe{
    height:300px;
    width:300px;
    background:lemonchiffon;
    position: relative;
    }

    .picframe > div{
        display: inline-block;
    }

    .fa-xmark{
        position: absolute;
        top: 25px;
        right: 8px;
        z-index: 100;
        background-color: #ffffff;
        padding: 5px 7px 2px;
        color: #000;
        cursor: pointer;
        font-size: 23px;
        line-height: 10px;
        border-radius: 50%;
    }

    .del {
        background-color:red;
        font-size: 2.5rem;
    }

</style>
<div class="row">
<div class="col-4 p-2">
    <div class="picframe" data-index="0">
        <i class="fa fa-times del curP" onclick="del(this)" aria-hidden="true"></i>
    </div>
</div>
<div class="col-4 p-2">
    <div class="picframe">
        <i class="fa fa-times del curP" onclick="del(this)" aria-hidden="true"></i>
    </div>
</div>
<div class="col-4 p-2">
    <div class="picframe">
        <i class="fa fa-times del curP" onclick="del(this)" aria-hidden="true"></i>
    </div>
</div>
</div>

@endsection
@section('customJsBottom')
<script>
    var gallery = ['a','b','c'];
    // deleteInit();
    $('.fa-times').hide();
    $('.picframe').mouseover(function(e){
        let target = $(this).children('.fa-times');
        target.show();
        target.mouseout(function(e){
            $(this).hide();
        })
        $('.picframe').mouseout(function(e){
            target = $(this).children('.fa-times').hide();
        })
    })

    // function deleteInit(){
    //     $('.del').unbind('click');
    //     $('.del').bind('click',function(){
    //         let t = $(this).parents('.picframe');
    //         console.log(t);
    //         t.remove();
    //     });
    // }

    function del(e){
        let target = $(e).parents('.picframe');
        target.remove();
        index = target.data("index");
        console.log(gallery);
        delete gallery[index];
        console.log(gallery);
    }
</script>

@endsection
