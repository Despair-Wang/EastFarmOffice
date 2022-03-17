(function ($) {
    var crop_width = 480,
        crop_height = 480,
        crop_type = "square",
        preview_size = 480,
        compress_ratio = 0.5,
        img_type = "png",
        oldImg = new Image(),
        file;
    var myCrop = $("#oldImg").croppie({
        viewport: {
            width: crop_width,
            height: crop_height,
            type: crop_type,
        },
        boundary: {
            width: preview_size,
            height: preview_size,
        },
    });

    function read_file(input) {
        if (input.files && input.files[0]) {
            file = input.files[0];
        } else {
            alert("抱歉，您的瀏覽器不支援此功能");
            return;
        }

        if (file.type.indexOf("image") == 0) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var oldImgDataUrl = e.target.result;
                oldImg.src = oldImgDataUrl;
                myCrop.croppie("bind", {
                    url: oldImgDataUrl,
                });
            };

            reader.readAsDataURL(file);
        } else {
            alert("請上傳正確的圖片檔案");
        }
    }

    function display_cropImg(src) {
        var html = `<img id="pic" src="${src}"/>`;
        $("#showCover").html(html);
        $("#coverUpload").val(src);
        $("#oldImg_frame").css("display", "none");
    }

    $("#cover").on("change", function () {
        $("#oldImg_frame").css("display", "flex");
        $("#oldImg").show();
        read_file(this);
    });

    $("#crop_img").on("click", function () {
        myCrop
            .croppie("result", {
                type: "canvas",
                format: img_type,
                quality: compress_ratio,
            })
            .then(function (src) {
                display_cropImg(src);
            });
    });

    $("#cancel").on("click", function () {
        $("#oldImg_frame").css("display", "none");
        $("#oldImg").hide();
    });
})(jQuery);
