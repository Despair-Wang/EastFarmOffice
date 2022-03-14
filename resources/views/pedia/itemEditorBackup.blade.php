@extends('layouts.backend')
@section('title', '百科項目－編輯')
@section('h1', '百科項目－編輯')
@section('content')
    <section class="pediaEditor">
        <div id="itemEditor">
            <div>
                <h3 class="h3">項目名稱</h3>
                <input type="text" id="name">
            </div>
            <div>
                <h3 class="h3">項目代表圖片</h3>
                <div>
                    <img style="width:300px">
                </div>
                <button class="btn btn-primary" id="deleteImage">刪除圖片</button>
                <input type="file" id="image">
                <input type="hidden" id="oldImage">
            </div>
            <div>
                <h3 class="h3">項目分類</h3>
                <select id="category">
                    <option value="-">請選擇一個分類</option>
                    @forelse ($categories as $cate)
                        <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                    @empty
                        <option value="-">無分類</option>
                    @endforelse
                </select>
            </div>
            <div>
                <h3 class="h3">項目標籤</h3>
                <select id="tag">
                    <option value="-">請選擇一個標籤</option>
                    @forelse ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @empty
                        <option value="-">無標籤</option>
                    @endforelse
                </select>
                <div id="addedTag"></div>
            </div>
            <div class='ali-r'>
                <input type="hidden" id="itemId">
                <input type="hidden" id="fatherId">
                <button class="btn btn-primary" id="createItem">儲存</button>
                <button class="btn btn-primary" id="resetItem">重寫</button>
            </div>
        </div>
        <div id="itemDisplay" style="display: none">
            <h2 class="h2"></h2>
            <img style="width:300px" src="" alt="">
            <h3 class="h3"></h3>
            <div class="addedTag">
            </div>
            <div class="ali-r">
                <button class="btn btn-primary" id="editItem">編輯</button>
            </div>
        </div>
    </section>
    <section>
        <h3 class="h3">項目內容</h3>
        <div id="contentArea">
            <form id="c-1" class="c-Box">
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
        </form>
        <div class="ali-r">
            <button class="btn btn-outline-primary my-3" id="addContent">增加內容項目</button>
        </div>
    </section>
    <section>
        <h3 class="h3">項目畫廊</h3>
        <div id="galleryArea" class="row">
            <div id="gallery-1" class="col-6 col-md-3 pb-3">
                <input type="hidden" class="type" value="local">
                <div class="choosePageBox row">
                    <div class="choosePage col-6 show" data-ctrl="local">本地上傳</div>
                    <div class="choosePage col-6" data-ctrl="url">外部連結</div>
                </div>
                <div class="uploadChoose">
                    <div class="page show" data-target="local">
                        <img src="" class="img-fluid">
                        <label>圖片上傳</label>
                        <input type="file" class="galleryUpload">
                    </div>
                    <div class="page" data-target="url">
                        <img src="" class="img-fluid">
                        <label>外部連結網址</label>
                        <input type="text" class="UrlUpload">
                    </div>
                </div>
                <label>說明</label>
                <div class="textarea"></div>
            </div>
            <div id="addGallery" class="col-6 col-md-3">
                <div>
                    <div></div>
                    <div></div>
                </div>
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
            galleryImgUpload();
            addGallery();
            addContentImg();
            useRemark();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            })

            $('#tag').on('change', () => {
                addTag();
            })

            $('#addContent').click(function() {
                let count = $('#contentArea').find('.c-Box');
                let id = parseInt($(count[count.length - 1]).attr('id').replace('c-', '')) + 1;
                let item = `
                <div id="c-${id}" class="c-Box">
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
                        <ul></ul>
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
                </div>`;
                $('#contentArea').append(item);
                addContentImg();
                useRemark();
            })

            $('#image').change(function(e) {
                let file = e.target.files[0];
                if (file['type'].indexOf('image') >= 0) {
                    let url = window.URL.createObjectURL(file);
                    $(this).parent('div').find('img').attr('src', url);
                    if (f.has('image')) {
                        f.delete('image')
                    }
                    f.append('image', file);
                } else {
                    alert('非圖片格式或格式太前衛');
                }
            })

            $('#addGallery').click(function() {
                let id = parseInt(($(this).prev('div').attr('id')).replace('gallery-', '')) + 1,
                    dom = `
                <div id="gallery-${id}" class="col-6 col-md-3 pb-3">
                <input type="hidden" class="type" value="local">
                <div class="choosePageBox row">
                    <div class="choosePage col-6 show" data-ctrl="local">本地上傳</div>
                    <div class="choosePage col-6" data-ctrl="url">外部連結</div>
                </div>
                <div class="uploadChoose">
                    <div class="page show" data-target="local">
                        <img src="" class="img-fluid">
                        <label>圖片上傳</label>
                        <input type="file" class="galleryUpload">
                    </div>
                    <div class="page" data-target="url">
                        <img src="" class="img-fluid">
                        <label>外部連結網址</label>
                        <input type="text" class="UrlUpload">
                    </div>
                </div>
                <label>說明</label>
                <div class="textarea"></div>
            </div>`;
                $(this).before(dom);
                addGallery();
                galleryImgUpload();
            })

            $('#createItem').click(function() {
                createItem();
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

            $('#editItem').click(function() {
                f.delete('name');
                f.delete('category');
                f.delete('image');
                f.delete('tags');
                f.delete('oldImage');
                $('#itemDisplay').hide();
                $('#itemEditor').show();
            })

            $('#deleteImage').click(function() {
                f.delete('image');
                f.delete('oldImage');
                $('#oldImage').val('');
                $('#image').val('');
                $('#image').parent('div').find('img').attr('src','');
            })
        })

        function createItem() {
            let name = $('#name').val(),
                category = $('#category').find(':selected').val(),
                tags = new Array(),
                id = $('#itemId').val(),
                oldImage = $('#oldImage').val();
            tag = $('#addedTag').find('.tag'),
                url = '';
            tag.each(function() {
                tags.push($(this).data('tag-id'));
            })
            f.append('name', name);
            f.append('category', category);
            f.append('oldImage', oldImage);
            tags = JSON.stringify(tags);
            f.append('tags', tags);
            if(id == ''){
                url = '/api/pedia/create';
            }else{
                url = `/api/pedia/${id}/update`;
            }
            $.ajax({
                url: url,
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: f,
                success(result) {
                    if (result['state'] == 1) {
                        alert('建立成功');
                        itemDisplay(result['data']['item'],result['data']['tags']);
                    } else {
                        console.log(result['msg']);
                        console.log(result['data']);
                    }
                },
                error(result) {
                    console.log(result);
                }
            })
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
                let id = ($(this).parents('.c-Box').attr('id')).replace('c-', '');
                let subId = parseInt(($(this).parents('form').find('.c-Image')).length) + 1;
                let item = `
            <div id="c-img-${id}-${subId}" class="c-Image col-6 col-md-3">
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
                let file = e.target.files[0],
                form = new FormData($(this).parents('form')[0]);
                if (file['type'].indexOf('image') >= 0) {
                    // let id = $(this).parent('.c-Image').attr('id'),
                    let id = "001",
                        url = window.URL.createObjectURL(file);
                    $(this).prev('div').children('img').attr('src', url);
                    if (form.has(id)) {
                        form.delete(id);
                    }
                    form.append(id, file);
                    console.log(form.getAll(id));
                } else {
                    alert('非圖片格式或格式太前衛');
                }
            })
        }

        function galleryImgUpload() {
            $('.galleryUpload').unbind('change');
            $('.galleryUpload').change(function(e) {
                let file = e.target.files[0];
                if (file['type'].indexOf('image') >= 0) {
                    let url = window.URL.createObjectURL(file),
                        id = $(this).parent('div').attr('id');
                    $(this).prevAll('img').attr('src', url);
                    if (f.has(id)) {
                        f.delete(id);
                    }
                    f.append(id, file);
                } else {
                    alert('非圖片格式或格式太前衛');
                }
            })
        }

        function addGallery() {
            $('.choosePage').unbind('click');
            $('.choosePage').click(function() {
                $(this).siblings().removeClass('show');
                $(this).addClass('show');
                let target = $(this).data('ctrl');
                $(this).parent().next().find('.page[data-target=' + target + ']').addClass('show');
                $(this).parent().next().find('.page[data-target!=' + target + ']').removeClass('show');
                $(this).parent('div').prev('input').val(target);
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

        function itemDisplay(item,tags){
            let t = $('#itemDisplay'),
            edit = $('#itemEditor');
            t.find('.h2').html(item['name']);
            t.find('.h3').html(item['category']);
            t.find('img').attr('src',item['image']);
            $('#oldImage').val(item['image']);
            $('#itemId').val(item['id']);
            $('#fatherId').val(item['fatherId']);
            let tagBox = t.find('.addedTag');
            tagBox.html('');
            tags.forEach(e => {
                tagBox.append(`<div class="tagBox show">${e['name']}</div>`)
            });
            edit.hide();
            t.show();
        }

        function createContent(target){
            let fatherId = $('#fatherId').val(),
            area = target.parents('.c-Box'),
            sort = (area.attr('id')).replace('c-', ''),
            title = area.find('.titles').val(),
            content = area.find('.contents').val(),
            gallery =
            gelleries = new Array(),
            remarks = new Array();
        }
    </script>
@endsection
