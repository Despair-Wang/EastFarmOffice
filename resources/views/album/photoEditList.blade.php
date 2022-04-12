@extends('layouts.backend')
@section('title',$album->name)
@section('h1',$album->name)
@section('content')
<section>
    <h4 class="ali-r">建立日期：{{ $album->getCreateDay() }}</h4>
    <input type="hidden" id="id" value="{{ $album->id }}">
</section>
<section>
    <div class="ali-r">
        <button class="btn btn-outline-primary" id="multDelBtn">批量刪除</button></div>
    <div class="row">
        @forelse ($photos as $photo)
        <div class="col-6 col-lg-3 photoBox" data-photo-id="{{ $photo->id }}">
            <div class="multDelete">
                <input class="multCheck" type="checkbox">
            </div>
            <div class="cover">
                <img src="{{ $photo->url }}" alt="">
            </div>
            <h5 class="h5">{{ $photo->title }}</h5>
        </div>
        @empty
            <h4 class="h4">無相片</h4>
        @endforelse
    </div>
</section>
<section>
    {!! $photos->links() !!}
</section>
<div id="createNew">
</div>
<div id="goBack">
</div>
<div id="multDelCtrl">
    <button class="btn btn-danger" id="multDelRun">刪除</button>
    <button class="btn btn-danger" id="multDelCancel">取消</button>
    <button></button>
</div>
<div id="fullPhoto">
    <div>
        <input type="hidden" id="photoId" value="">
        <img src="" alt="">
        <h4 class="h4">更換相片</h4>
        <input type="file" id="newPhoto">
        <h4 class="h4">照片標題</h4>
        <input type="text" id="photoTitle">
        <h4 class="h4">照片說明</h4>
        <input type="text" id="photoContent">
        <div class="ali-r">
            <button id="submit" class="btn btn-outline-light mt-2">修改</button>
            <button id="recover" class="btn btn-outline-light mt-2">復原</button>
            <button id="delete" class="btn btn-outline-light mt-2">刪除</button>
        </div>
        <button id="close" class="btn btn-outline-light mt-4 p-2 w-100">關閉</button>
    </div>
</div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/album/photoEditList.js')}}"></script>
@endsection
