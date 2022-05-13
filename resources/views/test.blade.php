@extends('layouts.basic')
@section('title','標題')
@section('content')
<input type="file" name="" id="upload">
<button id="call" class="btn btn-primary">call</button>
<div id="output"></div>
@endsection
@section('customJsBottom')
<script>
    $(()=>{
        var gallery = new Array();
        $('#upload').on('change', function(){
            readURL(this);
        })

        $('#call').click(function(){
            console.log(gallery);
            gallery.splice('pic0');
            // delete gallery['pic0'];
            console.log(gallery);
        })

        function readURL(input){
            if (input.files && input.files.length >= 0) {
                for(var i = 0; i < input.files.length; i++){
                    let index = 'pic' + i;
                    read_file(input.files[i],index);
                    var url = window.URL.createObjectURL(input.files[i]);
                    var img = $(`
                    <div class="picframe"  data-index='${i}'>
                        <img width='300' height='200' class='p-2 img-fluid' src="${url}">
                    </div>`)
                $("#output").append(img);
                    console.log('完成第',i)
                    // console.log("length:",input.files.length);
                }
            }else{
                var noPictures = $("<p>目前沒有圖片</p>");
                $("#output").append(noPictures);
            }
        }

        function read_file(input,index){
            var reader = new FileReader();
            reader.onload = function (e) {
                gallery[index] = e.target.result;
            }
            reader.readAsDataURL(input);
        }

        function deleteInit(){
            $(".del").unbind("click");
            $(".del").bind("click", function () {
            let target = $(this).parents(".picframe"),
                index = target.data("index");
                delete gallery[index];
            target.remove();
         });

        }
    })
</script>
@endsection
