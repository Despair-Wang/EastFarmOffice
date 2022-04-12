@extends('layouts.basic')
@section('title', '茶花千景')
@section('h1', '茶花千景')
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
                            </div>
                        </div>
                    @empty
                        <h3 class="h3">無相簿</h3>
                    @endforelse
                </div>
            </div>
            <div id="albumMenu" class="col-2">
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/album/list.js')}}"></script>
@endsection
