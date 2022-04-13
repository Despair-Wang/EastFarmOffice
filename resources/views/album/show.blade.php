@extends('layouts.basic')
@section('title',$album->name)
@section('h1',$album->name)
@section('content')
<section>
    <h4 class="ali-r">建立日期：{{ $album->getCreateDay() }}</h4>
    <input type="hidden" id="id" value="{{ $album->id }}">
</section>
<section>
    <div class="row">
        @forelse ($photos as $photo)
        <div class="col-6 col-lg-3 photoBox" data-photo-id="{{ $photo->id }}">
            <div class="cover">
                <img src="{{ $photo->url }}" alt="">
            </div>
            <h5 class="h5">{{ $photo->title }}</h5>
        </div>
        @empty
            <h4 class="h4">無相片</h4>
        @endforelse
    </div>
</section>
<section>
    {!! $photos->links() !!}
</section>
<div id="goBack"></div>
<div id="fullPhoto">
    <div>
        <div>
            <img src="" alt="">
            <h4 class="h4"></h4>
            <h6 class="h6"></h6>
        </div>
    </div>
</div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/album/show.js')}}"></script>
@endsection
