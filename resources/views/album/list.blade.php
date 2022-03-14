@extends('layouts.basic')
@section('title', '茶花千景')
@section('h1', '茶花千景')
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
                            </div>
                        </div>
                    @empty
                        <h3 class="h3">無相簿</h3>
                    @endforelse
                </div>
            </div>
            <div id="albumMenu" class="col-2">
            </div>
        </div>
    </section>
@endsection
@section('customJsBottom')
    <script>
        $(() => {
            $.ajax({
                url: '/api/getAlbumList',
                type: 'GET',
                success(result) {
                    let t = $('#albumMenu');
                    Object.keys(result).forEach(function(e) {
                        temp = `<div><ul><li class="yearItem">${e}年</li><ul>`;
                        Object.keys(result[e]).forEach(function(m) {
                            temp += `<li><a href="/o/album-list/${e}/${m}">${m}月(${result[e][m]})</a></li>`;
                        })
                        temp += '</ul></ul></div>';
                        t.append(temp);
                    })
                    setMenuAction();
                }
            })

            $('.albumListItem').click(function(e) {
                let id = $(this).children('div').data('album-id');
                location.href = `/o/album/${id}/photos/`;
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
    </script>
@endsection
