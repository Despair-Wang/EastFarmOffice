@extends('layouts.backend')
@section('title', $album->name . '-照片上傳')
@section('h1', $album->name . '-照片上傳')
@section('content')
    <section>
        <h4 class="ali-r">建立日期：{{ $album->getCreateDay() }}</h4>
        <input type="hidden" id="id" value="{{ $album->id }}">
    </section>
    <section>
        <div id="uploadArea">
            <div id="photoShowArea" class="row w-100">
            </div>
        </div>
        <h3 class="h3" id="uploadCaption">請將要上傳的照片拖移至此</h3>
        <label>單張照片上傳</label>
        <input type="file" id="upload">
    </section>
    <div id="goBack">
    </div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/album/upload.js')}}"></script>
@endsection
@section('customJsBottom')
    {{-- <script>
        $(() => {
            var md = new MoveDom(),
            albumId = $('#id').val();

            md.setBack(`/album/${albumId}/photos`);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            })

            let area = $('#uploadArea')[0];
            area.ondragover = function(e) {
                e.preventDefault();
            }

            area.ondrop = function(e) {
                e.preventDefault();
                let data = e.dataTransfer.files,
                    f = new FormData();
                for (let i = 0; i < data.length; i++) {
                    f.append('pic' + i, data[i]);
                }
                f.append('id', albumId);
                $.ajax({
                    url: '/api/album/uploadImg',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: f,
                    success(result) {
                        if (result['state'] == 1) {
                            result = result['data'];
                            for (let i = 0; i < result.length; i++) {
                                $('#photoShowArea').append(
                                    '<div class="col-6 col-lg-3 p-2"><img src="' + result[i] +
                                    '" class="img-fluid"></div>');
                            }
                        } else {
                            alert('上傳圖片失敗。狀況：' + result['msg']);
                            console.log(result)
                        }
                    }
                })
            }

            $('#upload').change(function(e) {
                let f = new FormData();
                f.append('pic', e.target.files[0]);
                f.append('id', $('#id').val());
                $.ajax({
                    url: '/api/album/uploadImg',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: f,
                    success(result) {
                        if (result['state'] == 1) {
                            result = result['data'];
                            for (let i = 0; i < result.length; i++) {
                                $('#photoShowArea').append(
                                    '<div class="col-6 col-lg-3 p-2"><img src="' + result[i] +
                                    '" class="img-fluid"></div>');
                            }
                            e.target.value = '';
                        } else {
                            alert('上傳圖片失敗。狀況：' + result['msg']);
                            console.log(result)
                        }
                    }
                })
            })
        })
    </script> --}}
@endsection
