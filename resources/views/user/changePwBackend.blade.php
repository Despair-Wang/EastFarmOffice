@extends('layouts.backend')
@section('title','更改密碼')
@section('t1','更改密碼')
@section('content')
    <div class="row">
        <div class="col-12">
            <label>新密碼</label>
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
@section('customJsBottom')
<script>
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
            let password = $('#password').val(),
                check = $('#confirmPassword').val();

            if(password != check){
                alert('密碼與確認密碼不一致');
                return false;
            }
            $.ajax({
                url:'/api/changePassword',
                type:'POST',
                data:{
                    password:password,
                    password_confirmation:check,
                },success(data){
                    if(data['state'] == 1){
                        alert('密碼已更新');
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
