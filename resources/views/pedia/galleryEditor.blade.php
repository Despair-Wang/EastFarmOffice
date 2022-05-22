@extends('layouts.backend')
@section('title', '畫廊照片上傳')
@section('h1', '畫廊照片上傳')
@section('content')
    <section>
        <input type="hidden" id="fatherId" value="{{ $fatherId }}">
        <h3 class="h3">項目畫廊</h3>
        <div id="showArea" class="row">
            @forelse ($galleries as $g)
                <div class="col-12 col-md-3" data-pic-id="{{ $g->id }}">
                    <div>
                        <div>
                            <i class="fa fa-times delete" aria-hidden="true"></i>
                        </div>
                        <div>
                            <img src="{{ asset($g->url) }}">
                        </div>
                        <p>{{ $g->caption }}</p>
                    </div>
                </div>
            @empty
                <p>尚無相片</p>
            @endforelse
        </div>
        <div id="galleryArea" class="row">
            <div id="gallery-1" class="col-6 col-md-3 pb-3 gallery">
                <input type="hidden" class="type" value="local">
                <div class="choosePageBox row">
                    <div class="choosePage col-6 show" data-ctrl="local">本地上傳</div>
                    <div class="choosePage col-6" data-ctrl="url">外部連結</div>
                </div>
                <div class="uploadChoose">
                    <div class="page show" data-target="local">
                        <img src="" class="img-fluid">
                        <label>圖片上傳</label>
                        <input type="file" class="galleryUpload">
                    </div>
                    <div class="page" data-target="url">
                        <img src="" class="img-fluid">
                        <label>外部連結網址</label>
                        <input type="text" class="UrlUpload">
                    </div>
                </div>
                <label>說明</label>
                <div class="textarea" contenteditable="true" placeholde="請輸入說明..."></div>
            </div>
            <div id="addGallery" class="col-6 col-md-3">
                <div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="ali-r">
            <button class="btn btn-outline-primary" id="submit">送出</button>
            <button class="btn btn-outline-primary" id="reset">重寫</button>
        </div>
    </section>
    <div id="goBack"></div>
@endsection
@section('customJsBottom')
<script type="text/javascript"src="{{ asset('js/pedia/gallery/edit.js') }}"></script>
@endsection
