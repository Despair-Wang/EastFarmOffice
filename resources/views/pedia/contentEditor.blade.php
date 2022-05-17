@extends('layouts.backend')
@section('title', '百科項目－內文編輯')
@section('h1', '百科項目－內文編輯')
@section('content')
    <section>
        <h3 class="h3">項目內容</h3>
        <div id="contentArea">
            <div id="c-Box" data-sort="{{ $sort }}"
            @isset($id)
            data-content-id="{{ $id->id }}"
            @endisset
            >
                <input type="hidden" id="fatherId" value="{{ $fatherId }}"">
                <h4 class="h4">標題</h4>
                <input id="title" type="text"
                @isset($id)
                value="{{ $id->title }}"
                @endisset
                >
                <h4 class="h4">內文</h4>
                <div class="textarea pedia">
                    <div class="remarkBtn">
                        <h5 class="h5">註釋</h5>
                        <div class="useRemark">
                            <div></div>
                            <div></div>
                        </div>
                        <div class="addRemarkBox">
                            <select class="remarkSelect">
                                <option value="-">-</option>
                            </select>
                            <button class="btn btn-info insertRemark">插入</button>
                        </div>
                    </div>
                    <div id="contents" contenteditable="true" placeholder="請輸入內文">
                    @isset($id)
                        {!! $id->content !!}
                    @endisset
                    </div>
                </div>
                <div class="remarkInputBox">
                    <ul>
                    @php
                        $remarks = $id->getRemarks();
                    @endphp
                    @isset($id)
                    @for ($i = 0; $i < count($remarks); $i++)
                        <li data-sort="{{ $i +1 }}">{!! $remarks[$i] !!}</li>
                    @endfor
                    @endisset
                    </ul>
                    <div class="border border-info m-4 p-3">
                        <label>註解內容</label>
                        <input type="text" class="remarkContent">
                        <label>附加連結</label>
                        <input type="text" class="remarkUrl">
                        <button class="btn btn-danger" id="createRemark">建立</button>
                    </div>
                </div>
                <div id="imageBox" class="my-3 mx-4 row">
                    @isset($id)
                        @forelse ($id->getGalleries() as $key => $g)
                        <div id="{{ $key }}" class="c-Image col-6 col-md-3 galleryItem">
                            <i class="fa fa-times del curP" aria-hidden="true"></i>
                            <div>
                                <img class="img-fluid" src="{{ $g[0] }}">
                            </div>
                            <h6 class="h6">{{ $g[1] }}</h6>
                        </div>
                        @empty
                        @endforelse
                    @endisset
                    <div class="col-12 ali-r">
                        <button class="btn btn-info addImage">插入圖片</button>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </section>
    @php
        print_r($id->getGalleries());
    @endphp
    <section>
        <div class="ali-r">
            @isset($id)
            <input type="hidden" id="active" value="update">
            @endisset
            <button class="btn btn-outline-primary" id="submit">送出</button>
            <button class="btn btn-outline-primary" id="reset">重寫</button>
        </div>
    </section>
    <div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script>
        var f = new FormData(),
            la,
            deleteImage = new Array();
        $(() => {
            var md = new MoveDom(),
            fatherId = $('#fatherId').val();
            la = new LoadAnime();
            addContentImg();
            useRemark();
            deleteInit();
            md.setBack(`/pedia/${fatherId}/preview`);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            })

            $('#addRemark').click(function() {
                let t = $('#remarkBox')
                if (t.height() == 0) {
                    t.addClass('show');
                } else {
                    t.removeClass('show');
                }
            })

            $('#createRemark').click(function() {
                let content = $(this).parent().find('.remarkContent'),
                    url = $(this).parent().find('.remarkUrl'),
                    sort = $(this).parents('.remarkInputBox').find('li').length + 1,
                    href = '';
                if (url.val() != '') {
                    href = `href="${url.val()}"`;
                }
                $(this).parents('.remarkInputBox').find('ul').append(`
            <li data-sort="${sort}"><a ${href}>[※${sort}]${content.val()}</a></li>
            `);
                content.val('');
                url.val('');
            })

            $("#contents").bind("paste", function (e) {
                e.preventDefault();
                let old = $(this).html();
                let t = e.originalEvent.clipboardData.getData("text");
                if(old == "" || old == " "){
                    $(this).html(t);
                }else{
                    $(this).html(old + t);
                }
            });

            $('#submit').click(function(){
                createContent();
            })
        })

        function inputFormat(content) {
            let start = /<div>/g,
                end = /<\/div>/g;
            content = content.replace("<div>", "<br>");
            content = content.replace(/<div>/g, "");
            content = content.replace(/<\/div>/g, "<br>");
            content = content.replace(" ","");
            return content;
        }

        function useRemark() {
            $('.useRemark').unbind('click');
            $('.useRemark').click(function() {
                let t = $(this).next('.addRemarkBox'),
                    list = t.find('.remarkSelect');
                if (t.width() == 0) {
                    list.html('');
                    let remarks = $(this).parents('.c-Box').children('.remarkInputBox').find('ul').find('li');
                    if (remarks.length > 0) {
                        remarks.each(function(e) {
                            let re = $(this);
                            list.append(`<option value="${re.data('sort')}">${re.html()}</option>`);
                        })
                        insertRemark();
                    } else {
                        list.append('<option value="-">無註釋</option>');
                    }
                    t.addClass('show');
                } else {
                    t.removeClass('show');
                }
            })
        }

        function insertRemark() {
            $('.insertRemark').unbind('click');
            $('.insertRemark').click(function() {
                var insertT, range, text;
                if (window.getSelection) {
                    insertT = window.getSelection();
                    if (insertT.getRangeAt && insertT.rangeCount) {
                        let t = $(this).prev().find(":selected").val(),
                            text = `[※${t}]`;
                        range = insertT.getRangeAt(0);
                        if($(range['startContainer'])[0]['parentElement'].getAttribute('id') == 'contents'){
                            range.insertNode(document.createTextNode(text));
                            insertT.collapseToEnd();
                        }else{
                            return false;
                        }
                    }
                }
            });
        }

        function addContentImg() {
            $('.addImage').unbind('click');
            $('.addImage').click(function(e) {
                e.preventDefault();
                let id = ($(this).parents('.c-Box').attr('id')),
                exists = $('#imageBox').find('.c-Image'),
                subId = 1;
                if(exists.length > 0){
                    subId = parseInt((exists[exists.length - 1 ].getAttribute('id')).replace('cImg','')) + 1;
                }
                let item = `
            <div id="cImg${subId}" class="c-Image col-6 col-md-3">
                <i class="fa fa-times float-right del curP" aria-hidden="true"></i>
                <div>
                    <img>
                </div>
                <input type="file" class="c-ImgUpload">
                <h5 class="h5 mt-2">說明文字</h5>
                <input type="text" class="imgMessage">
            </div>`;
                $(this).parent().before(item);
                contentImgUpload();
                deleteInit();
            })
        }

        function contentImgUpload() {
            $('.c-ImgUpload').unbind('change');
            $('.c-ImgUpload').change(function(e) {
                let file = e.target.files[0];
                if (file['type'].indexOf('image') >= 0) {
                    let id = $(this).parent('.c-Image').attr('id'),
                        url = window.URL.createObjectURL(file);
                    $(this).prev('div').children('img').attr('src', url);
                    if (f.has(id)) {
                        f.delete(id);
                    }
                    f.append(id, file);
                } else {
                    alert('非圖片格式或格式太前衛');
                }
            })
        }

        function deleteInit(){
            $('.del').off();
            $('.del').on('click',function() {
                let t = $(this).parent('.c-Image'),
                    index = t.attr('id');
                deleteImage.push(index);
                t.remove();
            });
        }

        function createContent(){
            la.run();
            let  fatherId = $('#fatherId').val(),
            id = $('#c-Box').data('content-id'),
            sort = $('#c-Box').data('sort'),
            title = $('#title').val(),
            content = $('#contents').html(),
            gContent = $('.imgMessage'),
            remark = $('.remarkInputBox > ul > li'),
            remarks = Array(),
            active = $('#active').val(),
            url = '';
            f.append('deleteImage',deleteImage);
            f.append('itemId',fatherId);
            f.append('sort',sort);
            f.append('title',title);
            f.append('content',content);
            gContent.each(function(){
                f.append($(this).parent().attr('id')+'c',$(this).val());
            });
            remark.each(function(){
                // f.append('re' + $(this).data('sort') , $(this).html());
                remarks.push($(this).html());
            })
            f.append('remarks',remarks);
            if(active == 'update'){
                url = `/api/pedia/content/${id}/update`;
            }else{
                url = '/api/pedia/content/create';
            }
            $.ajax({
                url:url,
                type:'POST',
                processData:false,
                contentType:false,
                cache:false,
                data:f,
                success:function(result){
                    la.stop();
                    if(result['state']==1){
                        alert('建立成功')
                        location.href=`/pedia/${fatherId}/preview`;
                    }else{
                        console.log(result['data']);
                    }
                },error:function(result){
                    console.log(result);
                }
            })
        }
    </script>
@endsection
