@extends('layouts.backend')
@section('title','商品類別一覽')
@section('h1','商品類別一覽')
@section('content')
    <div class="postListBox">
        <div class="row">
            <div class="col-10 row">
                <div class="col-4 col-md-3"><h2 class="h2">分類名稱</h2></div>
                <div class="col-6 col-md-9"><h2 class="h2">所屬分類</h2></div>
            </div>
        </div>
        @forelse ($categories as $g)
            <div id="{{ $g->id }}" class="listBox editPostItem row">
                <div class="col-10 row">
                    <div class="col-4 col-md-3">{{ $g->name }}</div>
                    <div class="col-6 col-md-9">{{ $g->getBelong() }}</div>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary edit">編輯</button>
                    <button class="btn btn-primary delete">刪除</button>
                </div>
            </div>
        @empty
            <p>暫無資料</p>
        @endforelse
    </div>
    <div id="createNew"></div>
@endsection
@section('customJsBottom')
    <script>

        var md = new MoveDom();
        $(()=>{
            md.setNew('/good/category/create');
            $('.edit').click(function(){
                let id = $(this).parents('.listBox').attr('id');
                location.href = `/good/category/${id}/edit`;
            })

            $('.delete').click(function(){
                let id = $(this).parents('.listBox').attr('id');
                categoryDelete(id);
            })
        })

        function categoryDelete(id){
            $.ajax({
                url:'/api/good/category/delete',
                type:'POST',
                data:{
                    id:id
                },success:function(data){
                    if(data['state'] == 1){
                        alert('刪除成功');
                        location.reload();
                    }else{
                        console.log(data['data'])
                    }
                },error:function(data){
                    console.log(data);
                }
            })
        }
    </script>
@endsection

