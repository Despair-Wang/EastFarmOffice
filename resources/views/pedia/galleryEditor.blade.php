@extends('layouts.backend')
@section('title', '百科項目－編輯')
@section('h1', '百科項目－編輯')
@section('content')
    <section>
        <input type="hidden" id="fatherId" value="{{ $fatherId }}">
        <h3 class="h3">項目畫廊</h3>
        <div id="showArea" class="row">
            @forelse ($galleries as $g)
                <div class="col-12 col-md-3" data-pic-id="{{ $g->id }}">
                    <div>
                        <div>
                            <i class="fa fa-times delete" aria-hidden="true"></i>
                        </div>
                        <div>
                            <img src="{{ asset($g->url) }}">
                        </div>
                        <p>{{ $g->caption }}</p>
                    </div>
                </div>
            @empty
                <p>尚無相片</p>
            @endforelse
        </div>
        <div id="galleryArea" class="row">
            <div id="gallery-1" class="col-6 col-md-3 pb-3 gallery">
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
                <div class="textarea" contenteditable="true" placeholde="請輸入說明..."></div>
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
        var f = new FormData(),
            fatherId = $('#fatherId').val();
        $(() => {
            galleryImgUpload();
            addGallery();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            })

            $('#submit').click(function() {
                galleryCreate();
            })

            $('#addGallery').click(function() {
                let id = parseInt(($(this).prev('div').attr('id')).replace('gallery-', '')) + 1,
                    dom = `
                <div id="gallery-${id}" class="col-6 col-md-3 pb-3 gallery">
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
                <div class="textarea" contenteditable="true" placeholde="請輸入說明..."></div>
            </div>`;
                $(this).before(dom);
                addGallery();
                galleryImgUpload();
            })

            $('.delete').click(function(){
                let id = $(this).parents('.col-12').data('pic-id');
                $.ajax({
                    url:`/api/pedia/gallery/${id}/delete`,
                    type:'GET',
                    success(result){
                        alert('刪除成功');
                        window.location.reload();
                    },
                    error(result){
                        alert('刪除失敗');
                        console.log(result);
                    }
                })
            })
        })

        function galleryImgUpload() {
            $('.galleryUpload').unbind('change');
            $('.galleryUpload').change(function(e) {
                let file;
                if (e.target.files && e.target.files[0]) {
                    file = e.target.files[0];
                } else {
                    return false;
                }
                if (file['type'].indexOf('image') >= 0) {
                    let url = window.URL.createObjectURL(file),
                        id = $(this).parents('.gallery').attr('id');
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

        function galleryCreate() {
            let target = $('.gallery'),
                galleries = Array();
            target.each(function() {
                let file = $(this).find('.galleryUpload').val(),
                    url = $(this).find('.UrlUpload').val(),
                    caption = $(this).find('.textarea').html(),
                    main = '';
                if (file == "" && url == "") {
                    alert('請選擇上傳檔案或給予連結')
                    return false;
                }
                if (url == "") {
                    main = $(this).attr('id');
                } else {
                    main = url;
                }
                if (caption == '') {
                    alert('請填寫照片說明')
                    return false;
                }

                let temp = ([main, caption]);
                galleries.push(temp);
            })
            f.append('galleries', galleries);
            f.append('fatherId', fatherId);
            $.ajax({
                url: `/api/pedia/gallery/create`,
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: f,
                success(result) {
                    if(result['state'] == 1){
                        alert('上傳成功')
                        window.location.reload();
                    }else{
                        console.log(result);
                    }
                },
                error(result) {
                    console.log(result);
                }
            })
        }
    </script>
@endsection
