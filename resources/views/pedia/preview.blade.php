@extends('layouts.backend')
@section('title', '預覽')
@section('h1', '預覽')
@section('content')
    <input type="hidden" id="id" value="{{ $item->fatherId }}">
    <section class="pediaEditor">
        <div id="itemDisplay">
            <div class="row">
                <div class="col-12 col-md-3">
                    <img style="width:300px" src="{{ $item->image }}" alt="">
                </div>
                <div class="col-12 col-md-9">
                    <h2 class="h2">名稱：{{ $item->name }}</h2>
                    <h3 class="h3">分類：{{$item->getCategoryName()}}</h3>
                    <div class="addedTag">
                    @forelse ($types as $type)
                        <div class="typeBox">
                            <h4 class="h4">{{ $type->name }}</h4>
                            @forelse ($type->getTagForItem($item->id) as $tag)
                                <h5 class="h5">{{ $tag->name }}</h5>
                            @empty
                            @endforelse
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
            <button class="btn btn-outline-primary" id="delete">刪除</button>
        </div>
    </section>
    <div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script>
        $(()=>{
          let id = $('#id').val(),
            md = new MoveDom();
            md.setBack('/pedia/list');
          $('#editItem').click(function(){
              location.href=`/pedia/item/${id}/edit`;
          })

          $('#addContent').click(function(){
            let sort = $('.pediaContent').length + 1;
            location.href=`/pedia/content/${id}/edit?sort=${sort}`;
          })

          $('#addGallery').click(function(){
            location.href=`/pedia/gallery/${id}/edit`;
          })

          $('#complete').click(function(){
              location.href='/pedia/list';
          })

          $('#delete').click(function(){
              //
          })

        })
    </script>
@endsection
