@extends('layouts.backend')
@section('title', $album->name . '-照片上傳')
@section('h1', $album->name . '-照片上傳')
@section('content')
    <section>
        <h4 class="ali-r">建立日期：{{ $album->getCreateDay() }}</h4>
        <input type="hidden" id="id" value="{{ $album->id }}">
    </section>
    <section>
        <div id="uploadArea">
            <div id="photoShowArea" class="row w-100">
            </div>
        </div>
        <h3 class="h3" id="uploadCaption">請將要上傳的照片拖移至此</h3>
        <label>單張照片上傳</label>
        <input type="file" id="upload">
    </section>
    <div id="goBack">
    </div>
    <div id="uploadAnime">
        <div>
            <div id="animeCore">
                <div></div>
                <div></div>
                <p>UPLOADING</p>
            </div>
        </div>
    </div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/album/upload.js')}}"></script>
@endsection
