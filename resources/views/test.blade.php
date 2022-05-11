@extends('layouts.basic');
@section('title','標題')
@section('content')
<button id="test" type="button">TEST</button>
@endsection
@section('customJsBottom')
<script>
    $(()=>{
        $('#test').click(function(){
            $.ajax({
                url:'/api/test',
                type:"GET",
                success(data){
                    if(data){
                        alert('login');
                    }else{
                        location.href='/login';
                    }
                },error(data){
                    console.log(data);
                }
            })
        })【
    })
</script>
@endsection