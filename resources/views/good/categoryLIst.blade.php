@extends('layouts.backend')
@section('title','商品類別一覽')
@section('t1','商品類別一覽')
@section('content')
    <div class="">
        @forelse ($categories as $g)
            <div id="{{ $g->id }}" class="listBox row">
                <div class="col-10 row">
                    <div class="col-4 col-md-3">{{ $g->name }}</div>
                    <div class="col-6 col-md-9">{{ $g->content }}</div>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary submit">編輯</button>
                    <button class="btn btn-primary delete">刪除</button>
                </div>
            </div>
        @empty
            <p>暫無資料</p>
        @endforelse
    </div>
@endsection
@section('customJsBottom')
    <script>
        $(()=>{
            $('.submit').click(function(){
                let id = $(this).parents('.listBox').attr('id');
                location.href = `/goode/category/${id}/edit`;
            })

            $('.delete').click(function(){
                let id = $(this).parents('.listBox').attr('id');
                delete(id);
            })
        })

        function delete(id){
            $.ajax({
                url:'/api/good/category/delete',
                type:'POST',
                data:{
                    id:id
                },success:function(data){
                    if(data['state'] == 1){
                        alert('刪除成功');
                        loaction.reload();
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

