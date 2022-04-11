@extends('layouts.backend')
@section('title','建立管理者')
@section('h1','建立管理者')
@section('content')
    <div class="row">
        <div class="col-12">
            <label>姓名</label>
            <input type="text" id="name" autocomplete="off">
        </div>
        <div class="col-12">
            <label>登入信箱</label>
            <input type="text" id="email" autocomplete="off">
        </div>
        <div class="col-12">
            <label>密碼</label>
            <input class="w-100" type="password" id="password" autocomplete="new-password">
            <span class="showPassword"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
        </div>
        <div class="col-12">
            <label>確認密碼</label>
            <input class="w-100" type="password" id="confirmPassword">
            <span class="showPassword"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
        </div>
        <div class="col-12">
            <button id="submit" class="btn btn-primary w-100 mt-4">建立</button>
        </div>
    </div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/user/admin/create.js')}}"></script>
@endsection
@section('customJsBottom')
{{-- <script>
    $(()=>{
        $('.showPassword').click(function(){
            let t = $(this).prev('input');
            if(t.hasClass('show')){
                t.attr('type','password');
                t.removeClass('show');
                $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
            }else{
                t.attr('type','text');
                t.addClass('show');
                $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
            }
        })

        $('#submit').click(function(){
            let name = $('#name').val(),
                email = $('#email').val(),
                password = $('#password').val(),
                check = $('#confirmPassword').val();
            if(name == ''){
                alert('請輸入姓名');
                return false;
            }

            if(email == ''){
                alert('請輸入登入用的信箱')
                return false;
            }

            if(password != check){
                alert('密碼與確認密碼不一致');
                return false;
            }
            $.ajax({
                url:'/api/admin/create',
                type:'POST',
                data:{
                    name:name,
                    email:email,
                    password:password,
                    password_confirmation:check,
                },success(data){
                    if(data['state'] == 1){
                        alert('管理者建立成功');
                        reset();
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

    function reset(){
        $('#name').val('');
        $('#email').val('');
        $('#password').val('');
        $('#confirmPassword').val('');
    }
</script> --}}
@endsection
