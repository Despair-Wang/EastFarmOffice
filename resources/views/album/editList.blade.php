@extends('layouts.backend')
@section('title','相簿一覽')
@section('h1','相簿一覽')
@section('content')
    <section>
        <div class="row">
            <div class="col-10">
                <div class="row">
                    @forelse ($albums as $album)
                    <div class="col-12 col-lg-3 albumListItem">
                        <div data-album-id="{{ $album->id }}">
                            <div class="cover">
                                {!! $album->getCover() !!}
                            </div>
                            <h4 class="h4">{{ $album->name }}</h4>
                            <h6 class="h6">{{ $album->getCreateDay() }}</h6>
                            <button class="btn btn-outline-primary edit">修改資訊</button>
                            <button class="btn btn-outline-primary upload">上傳照片</button>
                            <button class="btn btn-outline-primary showPhoto">檢視照片</button>
                            <button class="btn btn-outline-primary delete">刪除相簿</button>
                        </div>
                    </div>
                    @empty
                    <h3 class="h3">無相簿</h3>
                    @endforelse
                </div>
            </div>
            <div id="albumMenu" class="col-2"></div>
        </div>
    </section>
    {{ $albums->links() }}
    <div id="createNew">
    </div>
@endsection
@section('customJs')
    <script type="text/javascript" src="{{ asset('js/album/editList.js')}}"></script>
@endsection
@section('customJsBottom')
    {{-- <script>
        $(()=>{
            $.ajax({
                url: '/api/album/getList/admin',
                type: 'GET',
                success(result) {
                    let t = $('#albumMenu');
                    Object.keys(result).forEach(function(e) {
                        temp = `<div><ul><li class="yearItem">${e}年</li><ul>`;
                        Object.keys(result[e]).forEach(function(m) {
                            temp += `<li><a href="/album/list/${e}/${m}">${m}月(${result[e][m]})</a></li>`;
                        })
                        temp += '</ul></ul></div>';
                        t.append(temp);
                    })
                    setMenuAction();
                }
            })

            var md = new MoveDom();
            md.setNew('/album/edit');

            $('.albumListItem').click(function(e){
                let t = $(e.target);
                if(!t.hasClass('edit') && !t.hasClass('upload') && !t.hasClass('delete')){
                    let id = $(this).children('div').data('album-id');
                    location.href=`/album/${id}/photos/`;
                }
            })

            $('.edit').click(function(e){
                e.preventDefault();
                let id = $(this).parent('div').data('album-id');
                location.href=`/album/${id}/edit`;
            })

            $('.upload').click(function(e){
                e.preventDefault();
                let id = $(this).parent('div').data('album-id');
                location.href=`/album/${id}/photos/edit`;
            })

            $('.showPhoto').click(function(e){
                e.preventDefault();
                let id = $(this).parent('div').data('album-id');
                location.href=`/album/${id}/photos`;
            })

            $('.delete').click(function(e){
                e.preventDefault();
                let id = $(this).parent('div').data('album-id');
                $.ajax({
                    url:`/api/album/${id}/delete`,
                    type:'GET',
                    success(result){
                        if(result['state'] == 1){
                            alert('相簿刪除成功');
                            location.href='/album/list';
                        }else{
                            alert('刪除失敗');
                            console.log(result['data']);
                        }
                    }
                })
            })
        })

        function setMenuAction(){
            $('.yearItem').click(function(e){
                let t = $(this).next('ul');
                if(t.height() == 0){
                    $(this).addClass('open');
                    let height = t.find('li').height(),
                    len = t.find('li').length;
                    t.css('height',height * len);
                }else{
                    $(this).removeClass('open');
                    t.css('height',0);
                }
            })
        }
    </script> --}}
@endsection
