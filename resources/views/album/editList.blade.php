@extends('layouts.backend')
@section('title','相簿一覽')
@section('h1','相簿一覽')
@section('content')
    <section>
        <div class="row">
            <div class="col-10">
                <div class="row">
                    @forelse ($albums as $album)
                    <div class="col-12 col-lg-3 albumListItem">
                        <div data-album-id="{{ $album->id }}">
                            <div class="cover">
                                {!! $album->getCover() !!}
                            </div>
                            <h4 class="h4">{{ $album->name }}</h4>
                            <h6 class="h6">{{ $album->getCreateDay() }}</h6>
                            <button class="btn btn-outline-primary edit">修改資訊</button>
                            <button class="btn btn-outline-primary upload">上傳照片</button>
                            <button class="btn btn-outline-primary showPhoto">檢視照片</button>
                            <button class="btn btn-outline-primary delete">刪除相簿</button>
                        </div>
                    </div>
                    @empty
                    <h3 class="h3">無相簿</h3>
                    @endforelse
                </div>
            </div>
            <div id="albumMenu" class="col-2"></div>
        </div>
    </section>
    {{ $albums->links() }}
    <div id="createNew">
    </div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/album/editList.js')}}"></script>
@endsection
