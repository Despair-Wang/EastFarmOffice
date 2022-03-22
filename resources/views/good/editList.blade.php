@extends('layouts.backend')
@section('title','商品一覽')
@section('h1','商品一覽')
@section('content')
    <div id="gooodList">
    @forelse ($goods as $good)
        <div class="goodListBox row mb-2" data-good-id="{{ $good->serial }}">
                <div class="col-1">
                    <img src="{{ $good->cover }}">
                </div>
                <div class="col-6">
                    <h6 class="h6">{{ $good->serial }}</h6>
                    <h3 class="h3">{{ $good->name }}<span style="font-size:1rem;color:#666">({{$good->getState()}})</span></h3>
                </div>
                <div class="col-5 flex align-items-center justify-content-end">
                    <button class="btn btn-info px-4 mr-3 edit">修改</button>
                    <button class="btn btn-info px-4 mr-3 stock">庫存</button>
                    @if ($good->state == 1)
                    <button class="btn btn-info px-4 mr-3 putdown">下架</button>
                    @else
                    <button class="btn btn-info px-4 mr-3 putUp">上架</button>
                    @endif
                    <button class="btn btn-info px-4 delete">刪除</button>
                </div>
            </div>
            @empty
                <h3 class="h3">無商品</h3>
            @endforelse
        {!! $goods->links() !!}
    </div>
    <div id="createNew"></div>
@endsection
@section('customJsBottom')
<script>
    var md = new MoveDom();
    $(()=>{
        md.setNew('/good/create');

        $('.putUp').parents('.goodListBox').addClass('beGray');

        $('.edit').click(function(){
            let id = $(this).parents('.goodListBox').data('good-id');
            location.href=`/good/${id}/edit`;
        })

        $('.stock').click(function(){
            let id = $(this).parents('.goodListBox').data('good-id');
            location.href=`/good/${id}/stock`;
        })

        $('.putdown').click(function(){
            let id = $(this).parents('.goodListBox').data('good-id');
            $.ajax({
                url:`/api/good/${id}/putdown`,
                type:'GET',
                success(data) {
                    if(data['state'] == 1){
                        alert('商品已下架')
                        location.reload();
                    }else{
                        console.log(data['msg'])
                        console.log(data['data'])
                    }
                },error(data) {
                    console.log(data)
                }
            })
        })

        $('.putUp').click(function(){
            let id = $(this).parents('.goodListBox').data('good-id');
            $.ajax({
                url:`/api/good/${id}/putUp`,
                type:'GET',
                success(data) {
                    if(data['state'] == 1){
                        alert('商品已上架')
                        location.reload();
                    }else{
                        console.log(data['msg'])
                        console.log(data['data'])
                    }
                },error(data) {
                    console.log(data)
                }
            })
        })

        $('.delete').click(function(){
            if(confirm('是否真的要刪除商品？刪除後將無法復原')){
                let id=$(this).parents('.goodListBox').data('good-id');
                $.ajax({
                    url:`/api/good/${id}/delete`,
                    type:'GET',
                    success(data){
                        if(data['state'] == 1){
                            alert('商品已刪除')
                            location.reload();
                        }else{
                            console.log(data['msg']);
                            console.log(data['data']);
                        }
                    },error(data){
                        console.log(data);
                    }
                })
            }
        })
    })
</script>
@endsection
