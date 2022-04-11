@extends('layouts.basic')
@section('title','變更資訊')
@section('t1','變更資訊')
@section('content')
    @if(Auth::check())
        <div class="row">
            <div class="col-12">
                <label>姓名</label>
                <input class="w-100" type="text" id="name" autocomplete="off" value="{{ Auth::user()->name }}">
            </div>
            <div class="col-12">
                <label>登入信箱</label>
                <input class="w-100" type="text" id="email" autocomplete="off" value="{{ Auth::user()->email }}">
            </div>
            <div class="col-12">
                <button id="submit" class="btn btn-primary w-100 mt-4">更新</button>
            </div>
        </div>
    @endif
@endsection
@section('customJsBottom')
<script>
    $(()=>{
        $('#submit').click(function(){
            let name = $('#name').val(),
                email = $('#email').val();
            if(name == ''){
                alert('請輸入姓名')
                return false;
            }

            if(email == ''){
                alert('請輸入電子郵件')
                return false;
            }

            $.ajax({
                url:'/api/changeInfo',
                type: 'POST',
                data:{
                    name:name,
                    email:email,
                },success(data){
                    if(data['state'] == 1){
                        alert('資訊已更新');
                        location.href = '/dashboard';
                    }else{
                        alert(data['msg']);
                        console.log(data['data'])
                    }
                },error(data){
                    alert('Code Error,' + data['responseJSON']['message'])
                    console.log(data['responseJSON']['message'])
                    console.log(data)
                }
            })
        })
    })
</script>
@endsection
