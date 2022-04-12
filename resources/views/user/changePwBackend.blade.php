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
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/user/admin/changePassword.js')}}"></script>
@endsection
