@extends('layouts.backend')
@section('title', '商品編輯')
@section('h1', '商品編輯')
@section('content')
    <div>
        <input type="hidden" id="id" @isset($good) value="{{ $good->id }}" @endisset >
        <section>
            @isset($good)
                <h3 class="h3">{{ $good->serial }}</h3>
            @endisset
            <label>商品名稱</label>
            <input type="text" id="name"
            @isset($good)
            value="{{ $good->name }}"
            @endisset
            >
        </section>
        <section>
            <label>商品照</label>
            <div id="showCover">
                @isset($good)
                    <img src="{{ $good->cover }}">
                @endisset
            </div>
            <input type="file" id="cover">
            <input type="hidden" id="coverUpload">
        </section>
        <section>
            <label>商品分類</label>
            <select id="category">
                <option value="-">請選擇一個分類</option>
                @forelse ($categories as $c)
                    <option value="{{ $c->id }}"
                    @isset($good)
                        @if ($good->category == $c->id)
                            selected
                        @endif
                    @endisset
                    >{{ $c->name }}</option>
                @empty
                    <option value="-">暫無分類</option>
                @endforelse
            </select>
        </section>
        <section>
            <label>商品屬性</label>
            <div id="addedTag">
                @isset($tagForGood)
                    @forelse ($tagForGood as $t)
                    <div class="tagBox">
                        <div class="tag" data-tag-id="{{ $t->tagId }}">{{ $t->getTag->name }}</div>
                        <a class="removeTag">X</a>
                    </div>
                    @empty
                    @endforelse
                @endisset
            </div>
            <select id="tag">
                <option value="-">請選擇要增加的商品屬性</option>
                @forelse ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @empty
                @endforelse
            </select>
        </section>
        <section>
            <label>商品說明</label>
            <div id="caption" contenteditable="true" class="textarea" placeholder="請輸入商品敘述">
                @isset($good)
                    {!! $good->caption !!}
                @endisset
            </div>
        </section>
        <section>
            <label>商品款式</label>
            <div class="type">
                <div>
                    @if(isset($good))
                        <div class="row">
                            <div class="col-2">款式名稱</div>
                            <div class="col-5">款式說明</div>
                            <div class="col-2">價格</div>
                            <div class="col-1">庫存</div>
                        </div>
                        @foreach ($good->getTypes as $type)
                        <div class="typeBox row" id="type{{ $type->type }}">
                            <div class="col-2">
                                <input type="text" class="typeName" value={{ $type->name }} required>
                            </div>
                            <div class="col-5">
                                <input type="text" class="typeDescription" value="{{ $type->description }}">
                            </div>
                            <div class="col-2">
                                <label class="mr-2">$</label><input type="number" class="price" value="{{ $type->price }}">
                            </div>
                            <div class="col-2">
                                <p>{{ $type->getStock() }}個</p>
                            </div>
                            <div class="col-1">
                                <i class="fa fa-times float-right typeDel curP" aria-hidden="true"></i>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="typeBox" id="type1">
                        <i class="fa fa-times float-right typeDel curP" aria-hidden="true"></i>
                        <label>款式名稱</label>
                        <input type="text" class="typeName" value="基本款" required>
                        <label>款式說明</label>
                        <input type="text" class="typeDescription">
                        <label>單價</label>
                        <input type="number" class="price" value="0" required>
                        <label>庫存數量</label>
                        <input type="number" class="quantity" value="0" required>
                    </div>
                    @endif
                </div>
                <button class="btn btn-primary" id="addType">增加款式</button>
            </div>
        </section>
        <section>
            <label>其他相片</label>
            <div id="uploadArea">
                <div id="photoShowArea" class="row w-100">
                    @isset($good)
                        @if(gettype($good->gallery) == 'array')
                            @foreach ($good->gallery as $g)
                            <div class="col-6 col-md-3 mb-3 img">
                                <div>
                                    <i class="fa fa-times del" aria-hidden="true"></i>
                                    <img src="{{ $g }}">
                                </div>
                            </div>
                            @endforeach
                        @endif
                    @endisset
                </div>
            </div>
            <h3 class="h3" id="uploadCaption">請將要上傳的照片拖移至此</h3>
            <label>單張照片上傳</label>
            <input type="file" id="upload">
        </section>
        <section>
            <label class="curP" for="hot">熱推商品</label>
            <input class="curP" type="checkbox" id="hot">
            <p>※熱推商品設定數量超過顯示上限時，只會顯示最新上架的熱推商品</p>
        </section>
        <section>
            <div id="controlArea">
                <button class="btn btn-primary" id="submit">送出</button>
                <button class="btn btn-primary" id="reset">重寫</button>
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
    </div>
    <div id="goBack"></div>
@endsection
@section('customJs')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
@endsection
@section('customJsBottom')
    <script src="{{ asset('js/croppie.js') }}"></script>
    <script src="{{ asset('js/crop_img.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/good/backend/edit.js')}}"></script>
@endsection
