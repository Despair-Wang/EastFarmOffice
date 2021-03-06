@extends('layouts.backend')
@section('title', '畫廊照片上傳')
@section('h1', '畫廊照片上傳')
@section('content')
    <section>
        {{-- <h4 class="ali-r">建立日期：{{ $album->getCreateDay() }}</h4> --}}
        <input type="hidden" id="id" value="{{ $fatherId }}">
    </section>
    <section>
        <div id="uploadArea">
            <h3>請將要上傳的照片拖移至此</h3>
        </div>
        <label>單張照片上傳</label>
        <input type="file" id="upload">
        <div id="photoShowArea" class="row">
        </div>
    </section>
    <div id="goBack">
    </div>
@endsection
@section('customJsBottom')
    <script>
        $(() => {
            var md = new MoveDom(),
            fatherId = $('#id').val();

            md.setBack(`/pedia/${albumId}/preview`);

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
    </script>
@endsection
