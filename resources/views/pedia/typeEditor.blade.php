@extends('layouts.backend')
@section('title','百科屬性分類編輯')
@section('h1','百科屬性分類編輯')
@section('content')
<div>
    <label>屬性名稱</label>
    <input type="text" id="typeName"
    @isset($type)
        value="{{ $type->name }}"
    @endisset
    >
</div>
<div class="ali-r mt-3">
    @isset($type)
    <input type="hidden" id="action" value="update">
    <input type="hidden" id="typeId" value="{{ $type->id }}">
    @endisset
    <button id="submit" class="btn btn-outline-primary">送出</button>
    <button id="reset" class="btn btn-outline-primary">重寫</button>
</div>
<div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script type="text/javascript" src="{{ asset('js/pedia/type/edit.js')}}"></script>
@endsection
