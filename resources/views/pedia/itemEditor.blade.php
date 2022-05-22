@extends('layouts.backend')
@section('title', '百科項目－編輯')
@section('h1', '百科項目－編輯')
@section('content')
    <section class="pediaEditor">
        <div id="itemEditor" class="row">
            <div class="col-12 col-md-6">
                <h3 class="h3">項目名稱</h3>
                <input type="text" id="name"
                @isset($item)
                value="{{ $item->name }}"
                @endisset
                >
                <h3 class="h3">項目分類</h3>
                <select id="category">
                    <option value="-">請選擇一個分類</option>
                    @forelse ($categories as $cate)
                        <option value="{{ $cate->id }}"
                        @isset($item)
                            @if ($cate->id == $item->category)
                                selected="selected"
                            @endif
                        @endisset
                        >{{ $cate->name }}</option>
                    @empty
                        <option value="-">無分類</option>
                    @endforelse
                </select>
                <h3 class="h3">項目標籤</h3>
                <select id="tag">
                    <option value="-">請選擇一個標籤</option>
                    @forelse ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @empty
                        <option value="-">無標籤</option>
                    @endforelse
                </select>
                <div id="addedTag">
                @isset($item)
                    @forelse ($item->getTags as $tag)
                    <div class="tagBox">
                        <div class="tag" data-tag-id="{{ $tag->getTag['id'] }}">{{ $tag->getTag['name'] }}</div>
                        <a class="removeTag">X</a>
                    </div>
                    @empty
                    @endforelse
                @endisset
                </div>
            </div>
            <div class="col-12 col-md-6">
                <h3 class="h3">項目代表圖片</h3>
                <div id="showCover">
                @isset($item)
                    <img src="{{ $item->image }}">
                @endisset
                </div>
                {{-- <button class="btn btn-primary" id="deleteImage">刪除圖片</button> --}}
                <input type="file" id="cover" class="my-3">
                <input type="hidden" id="coverUpload">
                <input type="hidden" id="oldImage"
                    @isset($item)
                        value="{{ $item->image }}"
                    @endisset
                >
            </div>
            <div class='col-12'>
                <input type="hidden" id="itemId"
                @isset($item)
                    value="{{ $item['id'] }}"
                @endisset
                >
                <input type="hidden" id="fatherId"
                @isset($item)
                    value="{{ $item['fatherId'] }}"
                @endisset
                >
                <button class="btn btn-primary w-100 my-3" id="createItem">儲存</button>
                <button class="btn btn-primary w-100" id="resetItem">重寫</button>
            </div>
        </div>
    </section>
    <div id="oldImg_frame">
        <div class="row">
            <div class="col-12">
                <div id="oldImg">
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-center align-self-center">
                    <label id="crop_img" class="btn btn-outline-light w-auto mr-3">
                        <i class="fa fa-scissors"></i>&emsp;剪裁圖片
                    </label>
                    <label id="cancel" class="btn btn-outline-light w-auto">
                        取消
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div id="goBack"></div>
@endsection
@section('customJs')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
@endsection
@section('customJsBottom')
    <script src="{{ asset('js/croppie.js') }}"></script>
    <script src="{{ asset('js/crop_img.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/pedia/edit.js') }}"></script>
@endsection
