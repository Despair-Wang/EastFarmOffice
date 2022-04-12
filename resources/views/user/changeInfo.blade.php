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
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/user/user/changeInfo.js')}}"></script>
@endsection
