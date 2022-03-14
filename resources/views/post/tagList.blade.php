@extends('layouts.backend')
@section('title', '文章標籤一覽')
@section('h1', '文章標籤一覽')
@section('content')
    <section>
        <div style="text-align: right">
            <button class="btn btn-info" id="multDeleteButton">整批刪除</button>
        </div>
        <div id="postListBox">
            @foreach ($tags as $tag)
                <div class="editPostItem row" data-post-id="{{ $tag->id }}">
                    <div class="col-8">
                        <h2>{{ $tag->name }}</h2>
                    </div>
                    <div class="editArea col-3">
                        <a href="{{ '/post/tag/' . $tag->id . '/edit' }}">編輯</a>
                        <a class="postDelete" href="">刪除</a>
                    </div>
                    <div class="col-1 multDelBox">
                        <input type="checkbox" class="multDelete">
                    </div>
                </div>
            @endforeach
        </div>
        {!! $tags->links() !!}
    </section>
@endsection
@section('customJsBottom')
    <script>
        $(() => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            })

            $('.postDelete').click(function(e) {
                e.preventDefault();
                let id = $(this).parents(".editPostItem").data("post-id");
                $.ajax({
                    url: "/api/post/tag/deleteCheck",
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
                            let answer = confirm(
                                `刪除此分類，將會影響到${result['data']['count']}篇文章\n對象如下：\n${posts}是否要繼續刪除？`
                            )
                            if (answer) {
                                $.ajax({
                                    url: "/api/post/tag/delete",
                                    type: "POST",
                                    data: {
                                        data: id,
                                    },
                                    success(result) {
                                        if (result['state'] == 1) {
                                            alert(
                                                `操作結束，刪除了${result["data"]["success"]}項標籤，失敗${result["data"]["false"]}項`
                                                );
                                            location.href = "/post/tag/list";
                                        } else {
                                            console.log(result["data"]["xdebug_message"]);
                                        }
                                    }
                                })
                            }

                            // alert(
                            //     `操作結束，刪除了${result["data"]["success"]}項標籤，失敗${result["data"]["false"]}項`
                            // );
                            // location.href = "/post/tag/list";
                        } else {
                            console.log(result["data"]);
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
                    url: "/api/post/tag/deleteCheck",
                    type: "POST",
                    data: {
                        data: data,
                    },
                    success(result) {
                        if (result["state"] == 1) {
                            let posts = '',
                                post = result['data']['result'];
                            result['data']['result'].forEach(e => {
                                posts += '《' + e['title'] + '》\n'
                            });
                            let answer = confirm(
                                `刪除此分類，將會影響到${result['data']['count']}篇文章\n對象如下：\n${posts}是否要繼續刪除？`
                            )
                            if (answer) {
                                $.ajax({
                                    url: "/api/post/tag/delete",
                                    type: "POST",
                                    data: {
                                        data: data,
                                    },
                                    success(result) {
                                        if (result['state'] == 1) {
                                            alert(
                                                `操作結束，刪除了${result["data"]["success"]}項標籤，失敗${result["data"]["false"]}項`
                                                );
                                            location.href = "/post/tag/list";
                                        } else {
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
