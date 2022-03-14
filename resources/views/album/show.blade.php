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
        <img src="" alt="">
        <h4 class="h4"></h4>
        <h6 class="h6"></h6>
    </div>
</div>
@endsection
@section('customJsBottom')
<script>
    let t = $('#fullPhoto');
    t.hide();
    $(()=>{
        var md = new MoveDom();
        md.setBack('/o/album-list');
        $('.photoBox').click(function(){
            let id = $(this).data('photo-id');
            $.ajax({
                url:`/api/album/photo/${id}`,
                type:'GET',
                success(result){
                    if(result['state'] == 1){
                        t.find('img').attr('src',result['data']['url']);
                        t.find('h4').html(result['data']['title']);
                        t.find('h6').html(result['data']['content']);
                        // t.addClass('show');
                        t.fadeIn();
                        t.click(function(e){
                            e.preventDefault();
                            $(this).fadeOut();
                        })
                    }else{
                        alert(result['msg']);
                    }
                }
            })
        })
    })
</script>
@endsection
