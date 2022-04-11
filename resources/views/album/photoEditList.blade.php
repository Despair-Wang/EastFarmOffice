@extends('layouts.backend')
@section('title',$album->name)
@section('h1',$album->name)
@section('content')
<section>
    <h4 class="ali-r">建立日期：{{ $album->getCreateDay() }}</h4>
    <input type="hidden" id="id" value="{{ $album->id }}">
</section>
<section>
    <div class="ali-r">
        <button class="btn btn-outline-primary" id="multDelBtn">批量刪除</button></div>
    <div class="row">
        @forelse ($photos as $photo)
        <div class="col-6 col-lg-3 photoBox" data-photo-id="{{ $photo->id }}">
            <div class="multDelete">
                <input class="multCheck" type="checkbox">
            </div>
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
<div id="createNew">
</div>
<div id="goBack">
</div>
<div id="multDelCtrl">
    <button class="btn btn-danger" id="multDelRun">刪除</button>
    <button class="btn btn-danger" id="multDelCancel">取消</button>
    <button></button>
</div>
<div id="fullPhoto">
    <div>
        <input type="hidden" id="photoId" value="">
        <img src="" alt="">
        <h4 class="h4">更換相片</h4>
        <input type="file" id="newPhoto">
        <h4 class="h4">照片標題</h4>
        <input type="text" id="photoTitle">
        <h4 class="h4">照片說明</h4>
        <input type="text" id="photoContent">
        <div class="ali-r">
            <button id="submit" class="btn btn-outline-light mt-2">修改</button>
            <button id="recover" class="btn btn-outline-light mt-2">復原</button>
            <button id="delete" class="btn btn-outline-light mt-2">刪除</button>
        </div>
        <button id="close" class="btn btn-outline-light mt-4 p-2 w-100">關閉</button>
    </div>
</div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/album/photoEditList.js')}}"></script>
@endsection
@section('customJsBottom')
{{-- <script>
    let t = $('#fullPhoto');
    t.hide();
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content')
        }
    })
    $(()=>{
        showPhoto();
        $('#submit').click(function(){
            let id = $('#photoId').val(),
                title = $('#photoTitle').val(),
                content = $('#photoContent').val();
            $.ajax({
                url:`/api/album/photo/${id}/edit`,
                type:'POST',
                data:{
                    title:title,
                    content:content
                },success(result){
                    if(result['state'] == 1){
                        alert("更新成功")
                        location.href=$(location).attr('href');
                    }else{
                        alert("更新失敗")
                        console.log(result['data'] +':' + result['msg'])
                    }
                }
            })
        })

        $('#recover').click(function(){
            let id = $('#photoId').val();
            $.ajax({
                url:`/api/album/photo/${id}`,
                type:"GET",
                success(result){
                    if(result['state'] == 1){
                        setPhoto(result['data'])
                    }else{
                        alert("復原失敗");
                        console.log(result['data'] + ':' + result['msg'])
                    }
                }

            })
        })

        $('#delete').click(function(){
            let id = $('#photoId').val();
            $.ajax({
                url:`/api/album/photo/delete`,
                type:"POST",
                data:{
                    data:id,
                },
                success(result){
                    if(result['state'] == 1){
                        location.reload();
                    }else{
                        alert("照片刪除失敗");
                        console.log(result['data'] + ':' + result['msg'])
                    }
                }

            })
        })

        $('#multDelBtn').click(function(){
            $('.multDelete').show();
            $('.photoBox').click(function(e){
                let input = $(this).find('input');
                if(input.prop('checked')){
                    input.attr('checked',false);
                }else{
                    input.attr('checked',true);
                }
            })
            $('#multDelCtrl').show();
        })

        $('#multDelCancel').click(function(){
            $('.multDelete').hide();
            $('#multDelCtrl').hide();
            $('.photoBox').unbind('click');
            $('.multCheck').attr('checked',false);
            showPhoto();

        })

        $('#multDelRun').click(function(){
            let items = $('.multCheck'),
                data = new Array();
            items.each(function(e){
                if($(this).prop('checked')){
                   let id = $(this).parent('div').parent('div').data('photo-id');
                    data.push(id);
                }
            })
            $.ajax({
                url:'/api/album/photo/delete',
                type:'POST',
                data:{
                    data:data,
                },success(result){
                    if(result['state'] == 1){
                        alert('刪除成功')
                        location.reload();
                    }else{
                        alert('刪除失敗')
                        console.log(result['data']);
                    }
                }
            })
        })

        $('#newPhoto').change(function(e){
            let file = e.target.files[0],
                f = new FormData(),
                id = $('#photoId').val();
                f.append('pic',file);
                $.ajax({
                    url:`/api/album/photo/${id}/update`,
                    type:'POST',
                    processData:false,
                    contentType:false,
                    cache:false,
                    data:f,
                    success(result){
                        $(this).val('')
                        if(result['state'] == 1){
                            t.find('img').attr('src',result['data'])
                        }else{
                            let msg = "";
                            switch (result['msg']) {
                                case 'NO_FILE_UPLOAD':
                                    msg = "未傳送檔案"
                                    break;
                                case 'NOT_SUPPORT_TYPE':
                                    msg = "非圖片檔案或檔案格式不支援"
                                    break;
                                default:
                                    msg = result['msg'];
                                    break;
                                }
                            alert(msg);
                            console.log(result['data'])
                        }

                    }
                })
        })

        $('#close').click(function(){
            t.fadeOut();
        })

        let md = new MoveDom();

        let id = $('#id').val();
        md.setNew(`/album/${id}/photos/edit`)
        md.setBack("/album/list")
    })

    function setPhoto(photo){
        console.log('done');
        t.find('img').attr('src',photo['url']);
        $('#photoId').val(photo['id']);
        $('#photoTitle').val(photo['title']);
        $('#photoContent').val(photo['content']);
    }

    function showPhoto(){
        $('.photoBox').click(function(e){
            if(!$(e.target).hasClass('multDelete') && !$(e.target).hasClass('multCheck')){
                let id = $(this).data('photo-id');
                $.ajax({
                    url:`/api/album/photo/${id}`,
                    type:'GET',
                    success(result){
                        if(result['state'] == 1){
                            setPhoto(result['data']);
                            t.fadeIn();
                            t.find('').click(function(e){
                                e.preventDefault();
                                $(this).fadeOut();
                            })
                        }else{
                            alert(result['msg']);
                        }
                    }
                })
            }
        })
    }


</script> --}}
@endsection
