@extends('layouts.backend')
@section('title', '預覽')
@section('h1', '預覽')
@section('content')
    <input type="hidden" id="id" value="{{ $item->fatherId }}">
    <section class="pediaEditor">
        <div id="itemDisplay">
            <h2 class="h2">{{ $item->title }}</h2>
            <img style="width:300px" src="{{ $item->image }}" alt="">
            <h3 class="h3">{{$item->category}}</h3>
            <div class="addedTag">
            </div>
            <div class="ali-r">
                <button class="btn btn-primary" id="editItem">編輯</button>
            </div>
        </div>
    </section>
    <section>
        @forelse ($contents as $content)
            <div id="c{{ $content->sort }}" class="pediaContent">
                <h4>{{ $content->title }}</h4>
                <p>{{ $content->content }}</p>
                <ul>
                    @if (!is_null($content->remark))
                        @forelse ($content->getRemark() as $re)
                            <li>{!! $re !!}</li>
                        @empty
                        @endforelse
                    @endif
                </ul>
                <div class="row">
                    @forelse ($content->getGallery() as $g)
                        <div class="col-6 col-md-3">
                            <img src="{{ asset($g[0]) }}" alt="">
                            <p>{{ $g[1] }}</p>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
            <hr>
        @empty
        @endforelse
        <div class="ali-r">
            <button class="btn btn-outline-primary my-3" id="addContent">增加內容項目</button>
        </div>
    </section>
    <section>
        <div class="pediaGallery row">
            @forelse ($galleries as $g)
                <div class="col-6 col-md-3">
                    <h5 class="h5">{{ $g->title }}</h5>
                    <img src="" alt="">
                    <h6 class="h6">{{ $g->content }}</h6>
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
@endsection
@section('customJsBottom')
    <script>
        $(()=>{
          let id = $('#id').val();
          $('#editItem').click(function(){
              location.href=`/pedia/${id}/item/edit`;
          })

          $('#addContent').click(function(){
              let sort = $('.pediaContent').length + 1;
            location.href=`/pedia/${id}/content/edit?sort=${sort}`;
          })

          $('#addGallery').click(function(){
            location.href=`/pedia/${id}/gallery/edit`;
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
