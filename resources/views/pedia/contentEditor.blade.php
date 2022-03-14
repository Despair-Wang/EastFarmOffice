@extends('layouts.backend')
@section('title', '百科項目－內文編輯')
@section('h1', '百科項目－內文編輯')
@section('content')
    <section>
        <h3 class="h3">項目內容</h3>
        <div id="contentArea">
            <div id="{{ $sort }}" class="c-Box">
                <input type="hidden" id="fatherId" value="{{ $fatherId }}"">
                <h4 class="h4">標題</h4>
                <input class="titles" type="text">
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
                    <div id="c-1t" class="contents" contenteditable="true" placeholder="請輸入內文">
                    </div>
                </div>
                <div class="remarkInputBox">
                    <ul>
                    </ul>
                    <label>註解內容</label>
                    <input type="text" class="remarkContent">
                    <label>附加連結</label>
                    <input type="text" class="remarkUrl">
                    <button class="btn btn-danger" id="createRemark">建立</button>
                </div>
                <div class="my-3 row">
                    <div class="col-12 ali-r">
                        <button class="btn btn-info addImage">插入圖片</button>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </section>
    <section>
        <div class="ali-r">
            <button class="btn btn-outline-primary" id="submit">送出</button>
            <button class="btn btn-outline-primary" id="reset">重寫</button>
        </div>
    </section>
@endsection
@section('customJsBottom')
    <script>
        var f = new FormData();
        $(() => {
            addContentImg();
            useRemark();

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
                    sort = $(this).parent().find('li').length + 1,
                    href = '';
                if (url.val() != '') {
                    href = `href="${url.val()}"`;
                }
                $(this).parent().find('ul').append(`
            <li data-sort="${sort}"><a ${href}>[※${sort}]${content.val()}</a></li>
            `);
                content.val('');
                url.val('');
            })

            $('#submit').click(function(){
                createContent();
            })
        })

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
                        range.insertNode(document.createTextNode(text));
                        insertT.collapseToEnd();
                    }
                }
            });
        }

        function addContentImg() {
            $('.addImage').unbind('click');
            $('.addImage').click(function(e) {
                e.preventDefault();
                let id = ($(this).parents('.c-Box').attr('id'));
                let subId = parseInt(($(this).parents('.c-Box').find('.c-Image')).length) + 1;
                let item = `
            <div id="cImg${subId}" class="c-Image col-6 col-md-3">
                <div>
                    <img>
                </div>
                <input type="file" class="c-ImgUpload">
                <h5 class="h5 mt-2">說明文字</h5>
                <input type="text" class="imgMessage">
            </div>`;
                $(this).parent().before(item);
                contentImgUpload();
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

        function createContent(target){
            let fatherId = $('#fatherId').val(),
            area = $('.c-Box'),
            sort = area.attr('id'),
            title = area.find('.titles').val(),
            content = area.find('.contents').html(),
            gContent = $('.imgMessage'),
            remark = $('.remarkInputBox > ul > li'),
            remarks = Array();
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
            $.ajax({
                url:'/api/pedia/content/create',
                type:'POST',
                processData:false,
                contentType:false,
                cache:false,
                data:f,
                success:function(result){
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
