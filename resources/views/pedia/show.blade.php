@extends('layouts.basic')
@section('title',$item->name . '-茶花百科')
@section('h1',$item->name)
@section('content')
<section class="pediaEditor">
    <div id="itemDisplay">
        <div class="row">
            <div class="col-12 col-md-3">
                <img style="width:300px" src="{{ $item->image }}" alt="">
            </div>
            <div class="col-12 col-md-9">
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
    </div>
</section>
<hr class="my-3 border-primary">
<section>
    @forelse ($contents as $content)
        <div id="c{{ $content->sort }}" class="pediaContent">
            <h3 class="h3 border-left-6 border-primary pl-2">{{ $content->title }}</h3>
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
        </div>
        <hr class="my-3 border-primary">
    @empty
    <p>尚無任何內容項目</p>
    @endforelse
</section>
<section>
    <h3 class="h3 border-left-6 border-primary pl-2">相關圖像</h3>
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
        <p>尚無任何相關圖像</p>
        @endforelse
    </div>
</section>
<div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script>
        $(()=>{
            var md = new MoveDom();
            md.setBack('/o/pedia-list');
        })
    </script>
@endsection
