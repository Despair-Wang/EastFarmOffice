@extends('layouts.backend')
@section('title', $item->name . '-預覽')
@section('h1', $item->name . '-預覽')
@section('content')
    <input type="hidden" id="id" value="{{ $item->id }}">
    <section class="pediaEditor">
        <div id="itemDisplay">
            <div class="row">
                <div class="col-12 col-md-3">
                    <img style="width:300px" src="{{ $item->image }}" alt="">
                </div>
                <div class="col-12 col-md-9">
                    <h2 class="h2">名稱：{{ $item->name }}</h2>
                    <h5 class="h5">分類：{{$item->getCategoryName()}}</h5>
                    <div class="addedTag">
                    @forelse ($types as $type)
                        <div>
                            <h5 class="h5">{{ $type->name }}：
                            @forelse ($type->getTagForItem($item->id) as $tag)
                                <a class="mr-2" href="#">{{ $tag->name }}</a>
                            @empty
                            @endforelse
                            </h5>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
            </div>
            <div class="ali-r">
                <button class="btn btn-primary" id="editItem">編輯</button>
            </div>
        </div>
    </section>
    <hr class="my-3 border-primary">
    <section>
        @forelse ($contents as $content)
            <div id="c{{ $content->sort }}" class="pediaContent">
                <h4 class="h4">{{ $content->title }}</h4>
                <p>{!! $content->content !!}</p>
                <ul>
                    @if (!is_null($content->remark))
                        @forelse ($content->getRemarks() as $re)
                            <li class="text-secondary my-1">{!! $re !!}</li>
                        @empty
                        @endforelse
                    @endif
                </ul>
                <div class="row">
                    @if($content->getGalleries() != "")
                        @forelse ($content->getGalleries() as $g)
                            <div class="col-6 col-md-3 p-2">
                                <div class="border border-primary p-3 galleryItem">
                                    <div>
                                        <img src="{{ asset($g[0]) }}" alt="">
                                    </div>
                                    <h6 class="h6">{{ $g[1] }}</h6>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    @endif
                </div>
                <div class="ali-r">
                    <a class="btn btn-outline-primary my-3" href="{{ url('pedia/content/' . $item->id . '/edit/' . $content->id) }}" >編輯</a>
                </div>
            </div>
            <hr class="my-3 border-primary">
        @empty
        @endforelse
        <div class="ali-r">
            <button class="btn btn-outline-primary my-3" id="addContent">增加內容項目</button>
        </div>
    </section>
    <section>
        <div id="pediaGallery " class="row">
            @forelse ($galleries as $g)
                <div class="col-6 col-md-3 p-2">
                    <div class="border border-dark p-3 galleryItem">
                        <div>
                            <img src="{{ $g->url }}" alt="">
                        </div>
                        <h6 class="h6">{{ $g->caption }}</h6>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
        <div class="ali-r">
            <button class="btn btn-outline-primary my-3" id="addGallery">增加內容畫廊</button>
        </div>
    </section>
    <section>
        <div class="ali-r">
            <button class="btn btn-outline-primary" id="complete">確定</button>
            @if ($item->state == 1)
            <button class="btn btn-outline-primary" id="delete">刪除</button>
            @else
            <button class="btn btn-outline-primary" id="recover">復原</button>
            @endif
        </div>
    </section>
    <div id="goBack"></div>
@endsection
@section('customJsBottom')
<script type="text/javascript" src="{{ asset('js/pedia/listBackend.js')}}"></script>
@endsection
