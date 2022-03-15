@extends('layouts.backend')
@section('title', '文章分類一覽')
@section('h1', '文章分類一覽')
@section('content')
    <section>
        <div style="text-align: right">
            <button class="btn btn-info" id="multDeleteButton">整批刪除</button>
        </div>
        <div id="postListBox">
            @foreach ($cates as $cate)
                <div class="editPostItem row" data-post-id="{{ $cate->id }}">
                    <div class="col-8">
                        <h2>{{ $cate->name }}</h2>
                    </div class="col-3">
                    <div class="editArea col-3">
                        <a class="btn btn-primary px-3 mr-2" href="{{ '/post/category/' . $cate->id . '/edit' }}">編輯</a>
                        <a class="postDelete btn btn-primary px-3" href="">刪除</a>
                    </div>
                    <div class="col-1 multDelBox">
                        <input type="checkbox" class="multDelete">
                    </div>
                </div>
            @endforeach
        </div>
        {!! $cates->links() !!}
    </section>
    <div id="createNew"></div>
@endsection
@section('customJsBottom')
    <script>
        $(() => {
            var md = new MoveDom();
            md.setNew('/post/category/edit');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            })

            $('.postDelete').click(function(e) {
                e.preventDefault();
                let id = $(this).parents(".editPostItem").data("post-id");
                $.ajax({
                    url: "/api/post/category/deleteCheck",
                    type: "POST",
                    data: {
                        data: id,
                    },
                    success(result) {
                        if (result["state"] == 1) {
                            let posts = '',
                            post = result['data']['result'];
                            result['data']['result'].forEach(e => {
                                posts += '《' + e['title'] + '》\n'
                            });
                            let answer = confirm(`刪除此分類，將會影響到${result['data']['count']}篇文章\n對象如下：\n${posts}是否要繼續刪除？`)
                            if(answer){
                                $.ajax({
                                    url:"/api/post/category/delete",
                                    type:"POST",
                                    data:{
                                        data:id,
                                    },success(result){
                                        if(result['state'] == 1){
                                            alert(`操作結束，刪除了${result["data"]["success"]}項分類，失敗${result["data"]["false"]}項`);
                                            location.href = "/post/category/list";
                                        }else{
                                            console.log(result["data"]["xdebug_message"]);
                                        }
                                    }
                                })
                            }
                        } else {
                            console.log('data:' + result["data"] + ';massage:' + result['msg'] );
                        }
                    },
                });
            })

            $("#multDeleteButton").click(function() {
                let target = $(".multDelete"),
                    data = new Array();
                target.each(function() {
                    if ($(this).prop("checked")) {
                        let d = $(this).parents(".editPostItem").data("post-id");
                        data.push(d);
                    }
                });
                console.log(data);
                $.ajax({
                    url: "/api/post/category/deleteCheck",
                    type: "POST",
                    data: {
                        data: data,
                    },
                    success(result) {
                        if (result["state"] == 1) {
                            let posts = '',
                            post = result['data']['result'];
                            console.log(post);
                            result['data']['result'].forEach(e => {
                                posts += '《' + e['title'] + '》\n'
                            });
                            let answer = confirm(`刪除此分類，將會影響到${result['data']['count']}篇文章\n對象如下：\n${posts}是否要繼續刪除？`)
                            if(answer){
                                $.ajax({
                                    url:"/api/post/category/delete",
                                    type:"POST",
                                    data:{
                                        data:data,
                                    },success(result){
                                        if(result['state'] == 1){
                                            alert(`操作結束，刪除了${result["data"]["success"]}項分類，失敗${result["data"]["false"]}項`);
                                            location.href = "/post/category/list";
                                        }else{
                                            console.log(result["data"]["xdebug_message"]);
                                        }
                                    }
                                })
                            }
                        } else {
                            console.log(result["data"]);
                        }
                    },
                });
            });
        })
    </script>
@endsection
