@extends('layouts.backend')
@section('title','相簿建立')
@section('h1','相簿建立')
@section('content')
<section>
    <div>
        <label>相簿封面</label>
        <div id="showCover">
            <img class="img-fluid" src="
            @isset($album)
            {{ $album->cover }}
            @endisset
            " alt="">
        </div>
        <input type="file" id="cover">
    </div>
    <div>
        <label>相簿名稱</label>
        <input type="text" id="albumName"
        @if (isset($album))
        value="{{ $album->name }}"
        @endif
        >
    </div>
    <div>
        <label>相簿說明</label>
        <input type="text" id="albumContent"
        @if (isset($album))
        value="{{ $album->content }}"
        @endif
        >
    </div>
    <div class="ali-r">
        @if (isset($album))
        <input type="hidden" id="id" value="{{ $album->id }}">
        <input type="hidden" id="action" value="update">
        <button class="btn btn-outline-primary" id="submit">更新</button>
        <button class="btn btn-outline-primary" id="delete">刪除</button>
        @else
        <button class="btn btn-outline-primary" id="submit">建立</button>
        @endif
        <button class="btn btn-outline-primary" id="reset">重寫</button>
    </div>
</section>
<div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script>
        var f = new FormData();
        $(()=>{
            var md = new MoveDom();
            md.setBack('/album/list')
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content'),
                }
            })

            $('#cover').change(function(e){
                var file = e.target.files[0];
                if(file['type'].indexOf('image') >= 0){
                    let url = window.URL.createObjectURL(file);
                    $('#showCover').children('img').attr('src',url);
                    if(f.has('pic')){
                        f.delete('pic');
                    }
                    f.append('pic',file);
                }else{
                    alert('非圖片格式或格式太前衛');
                }
            })

            $('#reset').click(function(){
                $('#albumName').val('');
                $('#albumContent').val('');
            })

            $('#submit').click(function(){
                let name = $('#albumName').val(),
                content = $('#albumContent').val(),
                action = $('#action').val(),
                id = $('#id').val(),
                url = '';
                f.append('name',name);
                f.append('content',content);
                if(action == 'update'){
                    url = `/api/album/${id}/update`;
                }else{
                    url = '/api/album/create';
                }
                $.ajax({
                    url:url,
                    type:'POST',
                    processData:false,
                    contentType:false,
                    cache:false,
                    data:f,
                    success(result){
                        if(result['state'] == 1){
                            alert('相簿建立/更新完成');
                            location.href=`/album/${result['data']['id']}/photos/edit`;
                        }else{
                            console.log(result['data']);
                            console.log(result['msg']);
                        }

                    }
                })
            })

            $('#delete').click(function(){
                let id = $('#id').val();
                $.ajax({
                    url:`/api/album/${id}/delete`,
                    type:'GET',
                    success(result){
                        if(result['state'] == 1){
                            alert('相簿刪除成功');
                            location.href='/album/list';
                        }else{
                            alert('刪除失敗');
                            console.log(result['data']);
                        }
                    }
                })
            })
        })


    </script>
@endsection
