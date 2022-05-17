@extends('layouts.backend')
@section('title', '百科項目－編輯')
@section('h1', '百科項目－編輯')
@section('content')
    <section class="pediaEditor">
        <div id="itemEditor">
            <div>
                <h3 class="h3">項目名稱</h3>
                <input type="text" id="name"
                @isset($item)
                 value="{{ $item->name }}"
                @endisset
                >
            </div>
            <div>
                <h3 class="h3">項目代表圖片</h3>
                <div>
                    <img style="width:300px"
                    @isset($item)
                        src="{{ $item->image }}"
                    @endisset
                    >
                </div>
                <button class="btn btn-primary" id="deleteImage">刪除圖片</button>
                <input type="file" id="image">
                <input type="hidden" id="oldImage"
                    @isset($item)
                        value="{{ $item->image }}"
                    @endisset
                >
            </div>
            <div>
                <h3 class="h3">項目分類</h3>
                <select id="category">
                    <option value="-">請選擇一個分類</option>
                    @forelse ($categories as $cate)
                        <option value="{{ $cate->id }}"
                        @isset($item)
                            @if ($cate->id == $item->category)
                                selected="selected"
                            @endif
                        @endisset
                        >{{ $cate->name }}</option>
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
                <div id="addedTag">
                @isset($item)
                    @forelse ($item->getTags as $tag)
                    <div class="tagBox">
                        <div class="tag" data-tag-id="{{ $tag->getTag['id'] }}">{{ $tag->getTag['name'] }}</div>
                        <a class="removeTag">X</a>
                    </div>
                    @empty
                    @endforelse
                @endisset
                </div>
            </div>
            <div class='ali-r'>
                <input type="hidden" id="itemId"
                @isset($item)
                    value="{{ $item['id'] }}"
                @endisset
                >
                <input type="hidden" id="fatherId"
                @isset($item)
                    value="{{ $item['fatherId'] }}"
                @endisset
                >
                <button class="btn btn-primary" id="createItem">儲存</button>
                <button class="btn btn-primary" id="resetItem">重寫</button>
            </div>
        </div>
    </section>
@endsection
@section('customJsBottom')
    <script>
        var f = new FormData(),
            deleteList = new Array();
        $(() => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            })

            $('#tag').on('change', () => {
                addTag();
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

            $('#createItem').click(function() {
                createItem();
            })

            $('#deleteImage').click(function() {
                f.delete('image');
                f.delete('oldImage');
                $('#oldImage').val('');
                $('#image').val('');
                $('#image').parent('div').find('img').attr('src','');
            })
        })

        function tagInit(){
            let taglist = $('#tag > option'),
                t = $('.tag');
            // console.log(t);
            for (let i = 0; i < t.length; i++) {
                for (let j = 0; j < taglist.length; j++) {
                    if (taglist[j].value == t[i].dataset.tag-id) {
                        taglist[j].remove();
                    }
                }
            }
            $('.removeTag').click(function(e) {
                removeTag(e.target)
            });
        }

        function createItem() {
            let name = $('#name').val(),
                category = $('#category').find(':selected').val(),
                id = $('#itemId').val(),
                oldImage = $('#oldImage').val(),
                tags = new Array();
            tag = $('#addedTag').find('.tag'),
                url = '';
            tag.each(function() {
                tags.push($(this).data('tag-id'));
            })
            f.append('name', name);
            f.append('category', category);
            f.append('oldImage', oldImage);
            f.append('tags', JSON.stringify(tags));
            f.append('deleteList',JSON.stringify(deleteList));
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
                        let fatherId = result['data']['fatherId'];
                        location.href = `/pedia/${fatherId}/preview`;
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
                t = tb.children('.tag'),
                id = t.data('tag-id');
            $('#tag').append(`<option value="${id}">${t.text().replace('X','')}</option>`);
            tb.remove();
            deleteList.push(id);
        }
    </script>
@endsection
