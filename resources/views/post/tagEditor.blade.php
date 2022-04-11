@extends('layouts.backend')
@section('title', '文章標籤編輯')
@section('h1', '文章標籤編輯')
@section('content')
    <div>
        <label>分類名稱</label>
        <input type="text" id="tagName"
        @isset($tag)
            value="{{ $tag->name }}"
        @endisset
        >
    </div>
    <div>
        <label>分類說明</label>
        <input type="text" id="tagContent"
        @isset($tag)
            value="{{ $tag->content }}"
        @endisset
        >
    </div>
    <div class="ali-r mt-3">
        @isset($tag)
        <input type="hidden" id="action" value="update">
        <input type="hidden" id="tagId" value="{{ $tag->id }}">
        @endisset
        <button id="submit" class="btn btn-outline-primary">送出</button>
        <button id="reset" class="btn btn-outline-primary">重寫</button>
    </div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/post/tag/edit.js')}}"></script>
@endsection
@section('customJsBottom')
    {{-- <script>
        $(()=>{
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content')
                }
            })
            $('#submit').click(function(){
                let name = $('#tagName').val(),
                    content = $('#tagContent').val(),
                    id = $('#tagId').val(),
                    url = '';
                if($('#action').val() == 'update'){
                    url = `/api/post/tag/${id}/update`;
                }else{
                    url = `/api/post/tag/create`;
                }
                $.ajax({
                    url:url,
                    type:'post',
                    data:{
                        name:name,
                        content:content,
                    },success(result){
                        if(result['state'] == 1){
                            alert('建立/更新成功');
                            location.href="/post/tag/list";
                        }else{
                            alert('操作失敗');
                            console.log(result['data']);
                        }
                    }
                })
            })

            $('#reset').click(function(){
                $('#name').val('');
                $('#content').val('');
            })
        })
    </script> --}}
@endsection
