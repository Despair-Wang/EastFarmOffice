@extends('layouts.backend')
@section('title', '百科項目－內文編輯')
@section('h1', '百科項目－內文編輯')
@section('content')
    <section>
        <h3 class="h3">項目內容</h3>
        <div id="contentArea">
            <div id="c-Box" data-sort="{{ $sort }}"
            @isset($id)
            data-content-id="{{ $id->id }}"
            @endisset
            >
                <input type="hidden" id="fatherId" value="{{ $fatherId }}"">
                <h4 class="h4">標題</h4>
                <input id="title" type="text"
                @isset($id)
                value="{{ $id->title }}"
                @endisset
                >
                <h4 class="h4">內文</h4>
                <div class="textarea pedia">
                    <div class="remarkBtn">
                        <h5 class="h5">註釋</h5>
                        <div class="useRemark">
                            <div></div>
                            <div></div>
                        </div>
                        <div id="addRemarkBox">
                            <select id="remarkSelect">
                                <option value="-">-</option>
                            </select>
                            <button class="btn btn-info insertRemark">插入</button>
                        </div>
                    </div>
                    <div id="contents" contenteditable="true" placeholder="請輸入內文">
                    @isset($id)
                        {!! $id->content !!}
                    @endisset
                    </div>
                </div>
                <div id="remarkInputBox">
                    <ul>
                    @isset($id)
                    @php
                        $remarks = $id->getRemarks();
                    @endphp
                    @for ($i = 0; $i < count($remarks); $i++)
                        <li data-sort="{{ $i +1 }}">{!! $remarks[$i] !!}</li>
                    @endfor
                    @endisset
                    </ul>
                    <div class="border border-info m-4 p-3">
                        <label>註解內容</label>
                        <input type="text" class="remarkContent">
                        <label>附加連結</label>
                        <input type="text" class="remarkUrl">
                        <button class="btn btn-danger" id="createRemark">建立</button>
                    </div>
                </div>
                <div id="imageBox" class="my-3 mx-4 row">
                    @isset($id)
                        @forelse ($id->getGalleries() as $key => $g)
                        <div id="{{ $key }}" class="c-Image col-6 col-md-3 galleryItem">
                            <i class="fa fa-times del curP" aria-hidden="true"></i>
                            <div>
                                <img class="img-fluid" src="{{ $g[0] }}">
                            </div>
                            <h6 class="h6">{{ $g[1] }}</h6>
                        </div>
                        @empty
                        @endforelse
                    @endisset
                    <div class="col-12 ali-r">
                        <button class="btn btn-info addImage">插入圖片</button>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </section>
    <section>
        <div class="ali-r">
            @isset($id)
            <input type="hidden" id="active" value="update">
            @endisset
            <button class="btn btn-outline-primary" id="submit">送出</button>
            <button class="btn btn-outline-primary" id="reset">重寫</button>
        </div>
    </section>
    <div id="goBack"></div>
@endsection
@section('customJsBottom')
<script type="text/javascript" src="{{ asset('js/pedia/content/edit.js') }}"></script>
@endsection
