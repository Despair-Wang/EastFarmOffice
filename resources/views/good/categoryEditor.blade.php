@extends('layouts.backend')
@section('title','商品類別編輯')
@section('h1','商品類別編輯')
@section('content')
    <div class="">
        <label for="">分類名稱</label>
        <input type="text" id="name"
        @isset($id)
            value="{{ $id->name }}"
        @endisset
        >
        <label for="">所屬分類</label>
        <select id="sub">
            <option value="-">無所屬</option>
            @foreach ($categories as $g)
                @if(isset($id))
                    @if($id->id == $g->id)
                    @continue
                    @endif
                @endif
                <option value="{{ $g->id }}"
                @isset($id)
                    @if ($id->sub == $g->id)
                        selected
                    @endif
                @endisset
                    >{{ $g->name }}</option>
            @endforeach
        </select>
        <label for="">分類說明</label>
        <input type="text" id="cateContent"
        @isset($id)
            value="{{ $id->content }}"
        @endisset
        >
        <div class="row mt-3">
            <div class="col-6">
                @if(isset($id))
                <input type="hidden" id="id" value="{{ $id->id }}">
                <button class="btn btn-primary w-100" id="submit">更新</button>
                @else
                <button class="btn btn-primary w-100" id="submit">建立</button>
                @endif
            </div>
            <div class="col-6">
                <button class="btn btn-primary w-100" id="reset">重寫</button>
            </div>
        </div>
    </div>
    <div id="goBack"></div>
@endsection
@section('customJsBottom')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
            }
        })
        var md = new MoveDom();
        $(()=>{
            md.setBack('/good/category/list');
            $('#submit').click(function(){
                let id = $('#id').val(),
                name = $('#name').val(),
                sub = $('#sub').find(':selected').val(),
                content = $('#cateContent').val(),
                url = '';
                if(typeof(id) == 'undefined'){
                    url='/api/good/category/create';
                }else{
                    url = `/api/good/category/${id}/update`
                }
                $.ajax({
                    url:url,
                    type:'POST',
                    data:{
                        id:id,
                        name:name,
                        sub:sub,
                        content:content,
                    },success(result){
                        if(result['state']==1){
                            alert('建立成功');
                            location.href = '/good/category/list';
                        }else{
                            alert('建立失敗');
                            console.log(result['data'])
                        }
                    },error(result){
                        console.log(result);
                    }
                })
            })

            $('#reset').click(function(){
                reset();
            })
        })

        function reset(){
            let id = $('#id').val(''),
                name = $('#name').val(''),
                sub = $('#sub').find('option[value="-"]').attr('selected',true),
                content = $('#content').val('');
        }
    </script>
@endsection

