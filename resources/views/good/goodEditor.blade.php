@extends('layouts.backend')
@section('title', '商品編輯')
@section('h1', '商品編輯')
@section('content')
    <div>
        <input type="hidden" id="id" @isset($good) value="{{ $good->id }}" @endisset >
        <section>
            @isset($good)
                <h3 class="h3">{{ $good->serial }}</h3>
            @endisset
            <label>商品名稱</label>
            <input type="text" id="name"
            @isset($good)
             value="{{ $good->name }}"
            @endisset
            >
        </section>
        <section>
            <label>商品照</label>
            <div id="showCover">
                @isset($good)
                    <img src="{{ $good->cover }}">
                @endisset
            </div>
            <input type="file" id="cover">
            <input type="hidden" id="coverUpload">
        </section>
        <section>
            <label>商品分類</label>
            <select id="category">
                <option value="-">請選擇一個分類</option>
                @forelse ($categories as $c)
                    <option value="{{ $c->id }}"
                    @isset($good)
                        @if ($good->category == $c->id)
                            selected
                        @endif
                    @endisset
                    >{{ $c->name }}</option>
                @empty
                    <option value="-">暫無分類</option>
                @endforelse
            </select>
        </section>
        <section>
            <label>商品說明</label>
            <div id="caption" contenteditable="true" class="textarea" placeholder="請輸入商品敘述">
                @isset($good)
                    {!! $good->caption !!}
                @endisset
            </div>
        </section>
        <section>
            <label>商品款式</label>
            <div class="type">
                <div>
                    @if(isset($good))
                        <div class="row">
                            <div class="col-2">款式名稱</div>
                            <div class="col-5">款式說明</div>
                            <div class="col-2">價格</div>
                            <div class="col-1">庫存</div>
                        </div>
                        @foreach ($good->getTypes as $type)
                        <div class="typeBox row" id="type{{ $type->type }}">
                            <div class="col-2">
                                <input type="text" class="typeName" value={{ $type->name }} required>
                            </div>
                            <div class="col-5">
                                <input type="text" class="typeDescription" value="{{ $type->description }}">
                            </div>
                            <div class="col-2">
                                $<input type="number" class="price" value="{{ $type->price }}">
                            </div>
                            <div class="col-2">
                                <p>{{ $type->getStock() }}個</p>
                            </div>
                            <div class="col-1">
                                <i class="fa fa-times float-right typeDel curP" aria-hidden="true"></i>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="typeBox" id="type1">
                        <i class="fa fa-times float-right typeDel curP" aria-hidden="true"></i>
                        <label>款式名稱</label>
                        <input type="text" class="typeName" value="基本款" required>
                        <label>款式說明</label>
                        <input type="text" class="typeDescription">
                        <label>單價</label>
                        <input type="number" class="price" value="0" required>
                        <label>庫存數量</label>
                        <input type="number" class="quantity" value="0" required>
                    </div>
                    @endif
                </div>
                <button class="btn btn-primary" id="addType">增加款式</button>
            </div>
        </section>
        <section>
            <label>其他相片</label>
            <div id="uploadArea">
                <div id="photoShowArea" class="row w-100">
                    @isset($good)
                        @foreach ($good->gallery as $g)
                        <div class="col-6 col-md-3 mb-3 img">
                            <div>
                                <i class="fa fa-times del" aria-hidden="true"></i>
                                <img src="{{ $g }}">
                            </div>
                        </div>
                        @endforeach
                    @endisset
                </div>
            </div>
            <h3 class="h3" id="uploadCaption">請將要上傳的照片拖移至此</h3>
            <label>單張照片上傳</label>
            <input type="file" id="upload">
        </section>
        <section>
            <label class="curP" for="hot">熱推商品</label>
            <input class="curP" type="checkbox" id="hot">
            <p>※熱推商品設定數量超過顯示上限時，只會顯示最新上架的熱推商品</p>
        </section>
        <section>
            <div id="controlArea">
                <button class="btn btn-primary" id="submit">送出</button>
                <button class="btn btn-primary" id="reset">重寫</button>
            </div>
        </section>
        <div id="oldImg_frame">
            <div class="row">
                <div class="col-12">
                    <div id="oldImg">
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex justify-content-center align-self-center">
                        <label id="crop_img" class="btn btn-outline-light w-auto mr-3">
                            <i class="fa fa-scissors"></i>&emsp;剪裁圖片
                        </label>
                        <label id="cancel" class="btn btn-outline-light w-auto">
                            取消
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('customJs')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lc_switch.css') }}">
@endsection
@section('customJsBottom')
    <script src="{{ asset('js/croppie.js') }}"></script>
    <script src="{{ asset('js/crop_img.js') }}"></script>
    <script src="{{ asset('js/lc_switch.js') }}"></script>
    <script>
        var f = new FormData(),
            galleries = new Array();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
            }
        })
        $(() => {
            deleteTypeInit();

            $('#hot').lc_switch('開啟', '關閉');

            let area = $('#uploadArea')[0];
            area.ondragover = function(e) {
                e.preventDefault();
            }

            area.ondrop = function(e) {
                e.preventDefault();
                let data = e.dataTransfer.files;
                for (let i = 0; i < data.length; i++) {
                    if (data[i].type.indexOf('image') >= 0) {
                        let url = window.URL.createObjectURL(data[i]),
                            name = data[i].name,
                            img = `
                        <div class="col-6 col-md-3 mb-3 img" data-name="${name}">
                            <div>
                                <i class="fa fa-times del" aria-hidden="true"></i>
                                <img src="${url}">
                            </div>
                        </div>`;
                        $('#photoShowArea').append(img);
                        f.append(name, data[i]);
                        galleries.push(name);
                    } else {
                        alert('非圖片格式或格式太前衛');
                    }
                }
                deleteInit();
            }

            $('#upload').change(function(e) {
                var file;
                if(e.target.files || e.target.files[0]){
                    file = e.target.files[0]
                }
                if(file['type'].indexOf('image') >= 0){
                    let name = file['name'],
                    url = window.URL.createObjectURL(file),
                    img = `
                        <div class="col-6 col-md-3 mb-3 img" data-name="${name}">
                            <div>
                                <i class="fa fa-times del" aria-hidden="true"></i>
                                <img src="${url}">
                            </div>
                        </div>`;
                    $('#photoShowArea').append(img);
                    f.append(name,file);
                    galleries.push(name);
                    deleteInit();
                }else{
                    alert('非圖片格式或格式太前衛');
                }
            })

            $('#addType').click(function(){
                addType();
            })

            $('#submit').click(function(){
                submit();
            })

            $('#caption').bind('paste',function(e){
                e.preventDefault();
                let old = $(this).html();
                let t = e.originalEvent.clipboardData.getData('text');
                $(this).html(old + t);
            })
        })

        function inputFormat(content){
            let start = /<div>/g,
                end = /<\/div>/g;
            content = content.replace('<div>','<br>');
            content = content.replace(/<div>/g,'');
            content = content.replace(/<\/div>/g,'<br>');
            return content;
        }

        function deleteInit() {
            $('.del').unbind('click');
            $('.del').bind('click', function() {
                let target = $(this).parents('.img'),
                    name = target.data('name'),
                    index = galleries.indexOf(name);
                galleries.splice(index, 1);
                if (f.has(name)) {
                    f.delete(name);
                }
                target.remove();
            })
        }

        function deleteTypeInit(){
            $('.typeDel').unbind('click');
            $('.typeDel').bind('click',function(){
                $(this).parent().remove();
            });

        }

        function addType() {
            let t = $('.typeBox'),
            count = parseInt(($(t[t.length-1]).attr('id')).replace('type','')) + 1;
            console.log(count);
            let html = `
            <div class="typeBox" id="type${count}">
                <i class="fa fa-times float-right typeDel curP" aria-hidden="true"></i>
                <label>款式名稱</label>
                <input type="text" class="typeName" value="">
                <label>款式說明</label>
                <input type="text" class="typeDescription">
                <label>單價</label>
                <input type="number" class="price" value="0">
                <label>庫存數量</label>
                <input type="number" class="quantity" value="0">
            </div>`;
            $('#addType').prev().append(html);
            deleteTypeInit();
        }

        function submit(){
            let id = $('#id').val(),
            name = $('#name').val(),
            cover = $('#coverUpload').val(),
            category = $('#category').find(':selected').val(),
            caption = inputFormat($('#caption').html()),
            typeList = new Array(),
            hot = '',
            url ='',
            error = false,
            typeCount = 1;

            if($('#hot').next().hasClass('lcs_on')){
                hot = 1;
            }else{
                hot = 0;
            }

            $('.typeBox').each(function(){
                let name = $(this).find('.typeName').val(),
                description = $(this).find('.typeDescription').val(),
                quantity = $(this).find('.quantity').val(),
                price = $(this).find('.price').val(),
                typeId = $(this).attr('id');
                if(name == ''){
                    alert('請輸入樣式名稱');
                    error = true;
                }
                if(price == ''){
                    alert('請設定價格');
                    error = true;
                }else if(price <= 0){
                    alert('價格不可小於或等於0')
                    error = true;
                }
                if(quantity == ''){
                    alert('請輸入庫存量');
                    error = true;
                }else if(quantity <= 0){
                    alert('庫存量不可小於或等於0');
                    error = true;
                }
                if(description == ''){
                    description = '暫無說明';
                }
                if(error == true){
                    return false;
                }
                f.append(typeId,[typeCount,name,description,price,quantity]);
                typeList.push(typeId);
                typeCount++;
            })
            if(error == true){
                return false;
            }
            f.append('name',name);
            f.append('cover',cover);
            f.append('category',category);
            f.append('caption',caption);
            f.append('typeList',typeList);
            f.append('galleries',galleries);
            f.append('hot',hot);
            if(id == ''){
                url = '/api/good/create';
            }else{
                url = `/api/good/${id}/update`
            }
            $.ajax({
                url:url,
                type:'POST',
                processData:false,
                contentType:false,
                cache:false,
                data:f,
                success:function(data){
                    if(data['state'] == 1){
                        alert('商品建立成功');
                        location.href = `/good/list`;
                    }else{
                        console.log(data['msg'])
                        console.log(data['data'])
                    }
                },
                error:function(data){
                    console.log(data)
                }
            })
        }
    </script>
@endsection
