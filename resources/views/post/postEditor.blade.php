@extends('layouts.backend')
@section('title', 'POST EDITOR')
@section('customJs')
    <script src='https://cdn.jsdelivr.net/npm/wangeditor@latest/dist/wangEditor.min.js'></script>
@endsection
@section('content')
    <section>
        <label>文章標題：</label>
        <input type="text" id="title" @if (isset($action)) value = "{{ $post->title }}" @endif>
    </section>
    <section id="postEditArea2" class="row">
        <div class="col-12 col-md-4">
            <label>文章形象圖</label>
            <div id="uploadArea">
                <img id="indexImage" src="
                    @if (isset($action)) {{ $post->image }} @endif
                    " alt="">
            </div>
            <input type="file" id="imgUpload">
        </div>
        <div class="col-12 col-md-8">
            <section>
                <div>
                    <label>文章分類：</label>
                    <select name="" id="category">
                        <option>請選擇分類</option>
                        @forelse ($categories as $cate)
                        <option value="{{ $cate['id'] }}" @if (isset($action) && $cate['id'] == $post->category) selected @endif>
                            {{ $cate['name'] }}</option>
                            @empty
                            <option>No Anything</option>
                            @endforelse
                    </select>
                </div>
            </section>
            <section>
                <div>
                    <label>文章標籤：</label>
                    <select name="" id="tag">
                        <option value="-">請選擇新增的標籤</option>
                        @forelse ($tags as $tag)
                        <option value="{{ $tag['id'] }}">{{ $tag['name'] }}</option>
                    @empty
                    <option>No Anything</option>
                    @endforelse
                    </select>
                {{-- <button id="addTag">新增</button> --}}
                    <div id="addedTag">
                        @if (isset($action))
                            @if (isset($tagForPost) && count($tagForPost) != 0)
                                @foreach ($tagForPost as $tag)
                                    <div class="tagBox">
                                        <div class="tag" data-tag-id="{{ $tag['id'] }}">{{ $tag['name'] }}</div>
                                        <a class="removeTag">X</a>
                                    </div>
                                @endforeach
                            @else
                                @if (!is_null(Cache::get(Auth::id() . 'tag')))
                                    @foreach (Cache::get(Auth::id() . 'tag') as $tag)
                                        <div class="tagBox">
                                            <div class="tag" data-tag-id="{{ $tag['id'] }}">{{ $tag['name'] }}</div>
                                            <a class="removeTag">X</a>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </section>
    <section>
        <div id="mainInput"></div>
    </section>
    <section>
        <input type="hidden" id="version" value="
            @if (isset($action)) {{ $post->version }}
        @else
            0 @endif
            ">
        <div id="activeArea">
            @if (isset($action))
                <div id="action" data-action="{{ $action }}" data-post-id="{{ $post->id }}"></div>
            @else
                <button class="btn btn-outline-primary" id="draft">存成草稿</button>
            @endif
            <button class="btn btn-outline-primary" id="submit">送出</button>
            <button class="btn btn-outline-primary" id="reset">清空重寫</button>
        </div>
    </section>
    <div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script>
        $(() => {
            var md = new MoveDom();
            md.setBack('/post/list');
            const E = window.wangEditor;
            const e = new E('#mainInput');
            e.config.uploadImgServer = '/api/post/upload';
            e.config.uploadFileName = 'pic';
            e.config.uploadImgHeaders = {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
            }
            e.create();
            $('#tag').on('change', () => {
                addTag();
            })

            $('#submit').click(() => {
                createPost('normal');
            })

            $('#draft').click(() => {
                createPost('draft');
            })

            let up = document.querySelector('#uploadArea');
            up.ondragover = function(e) {
                e.preventDefault();
            }

            up.ondrop = function(e) {
                e.preventDefault();
                uploadImg(e.dataTransfer.files[0]);
            }

            $('#imgUpload').on('change', function() {
                let data = this.files[0];
                if (data.type.indexOf('image') == 0) {
                    uploadImg(data);
                } else {
                    alert('請上傳圖片或圖片格式過於前衛無法支援');
                }
            })

            $('#reset').click(function() {
                $('#title').val('');
                $('.w-e-text').html('');
                $('.removeTag').each(function() {
                    removeTag($(this));
                })
                $('#category').find(':selected').attr('selected', false);
                $('#indexImage').attr('src', '');
                $('#imgUpload').val('');
            })

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            });

            var postId = $('#action').data('post-id');
            if (postId != "" && typeof(postId) != 'undefined') {
                console.log($('#action').data('post-id'));
                getContent(postId);
            }
        })

        function uploadImg(file) {
            let f = new FormData();
            f.append('pic', file);
            $.ajax({
                url: '/api/post/upload',
                type: 'post',
                processData: false,
                contentType: false,
                cache: false,
                data: f,
                success(result) {
                    result = JSON.parse(result);
                    if (result['errno'] == 0) {
                        $('#indexImage').attr('src', result['data'][0]['url']);
                    } else {
                        alert('圖片上傳失敗');
                        console.log(result['data'][0]['url']);
                    }
                }
            })
        }

        function getContent(id) {
            // let postId = $('#action').data('post-id');
            $.ajax({
                url: `/api/post/${id}/getContent`,
                type: 'get',
                success(result) {
                    if (result['state'] == 1) {
                        let data = result['data'],
                            taglist = $('#tag > option'),
                            t = data['tags'];
                        // console.log(t);
                        $('.w-e-text').html(data['content']);
                        for (let i = 0; i < t.length; i++) {
                            for (let j = 0; j < taglist.length; j++) {
                                if (taglist[j].value == t[i]) {
                                    taglist[j].remove();
                                }
                            }
                        }
                        $('.removeTag').click(function(e) {
                            removeTag(e.target)
                        });
                    } else {
                        console.log(result['data']);
                    }
                }
            })
        }

        function addTag() {
            let tag = $('#tag').find(':selected');
            if (tag.val() == "-") {
                alert('請選擇一個有效的標籤');
                return false;
            }
            $('#addedTag').append(
                `<div class="tagBox"><div class="tag" data-tag-id="${tag.val()}">${tag.text()}</div><a class="removeTag">X</a></div>`
            )
            tag.remove();
            $('#tag').find('option[value="-"]').prop('selected', 'true');
            let x = $('.removeTag');
            x.off();
            x.on('click', function(e) {
                // console.log(e);
                removeTag(e.target);
            });
        }

        function removeTag(target) {
            let tb = $(target).parent('div'),
                t = tb.children('.tag');
            $('#tag').append(`<option value="${t.data('tag-id')}">${t.text().replace('X','')}</option>`);
            tb.remove();
        }

        function createPost(saveType) {
            let title = $('#title').val(),
                content = $('.w-e-text').html(),
                cate = $('#category').find(':selected').val(),
                postId = $('#action').data('post-id'),
                version = $('#version').val(),
                tagTemp = $('.tag'),
                tags = new Array(),
                picTemp = $('.w-e-text').find('img'),
                pics = new Array(),
                url = '',
                image = $('#indexImage').attr('src');
            picTemp.each(function() {
                pics.push($(this).attr('src'));
            })
            tagTemp.each(function() {
                tags.push($(this).data('tag-id'))
            })
            switch ($('#action').data('action')) {
                case 'rewrite':
                    url = '/api/post/create/rewrite';
                    break;
                case 'update':
                    url = '/api/post/create/update';
                    break;
                default:
                    url = '/api/post/create';
                    break;
            }
            $.ajax({
                url: url,
                type: 'post',
                data: {
                    title: title,
                    content: content,
                    category: cate,
                    postId: postId,
                    version: version,
                    tags: tags,
                    pics: pics,
                    image: image,
                    save:saveType,
                },
                success(result) {
                    if (result['state'] == '1') {
                        // alert('文章建立成功');
                        if(saveType == 'draft') {
                            alert('草稿建立完成')
                            location.href = '/post/list';
                        }else{
                            location.href = '/post/' + result['data']['id'] + '/preview';
                        }
                    } else {
                        alert('文章建立失敗:' + result['msg']);
                        console.log(result['data']);
                    }
                }
            })
        }
    </script>
@endsection
