var f = new FormData(),
    fatherId = $("#fatherId").val();
$(() => {
    galleryImgUpload();
    addGallery();
    var md = new MoveDom();
    md.setBack(`/pedia/${fatherId}/preview`);

    $("#submit").click(function () {
        galleryCreate();
    });

    $("#addGallery").click(function () {
        let id =
                parseInt(
                    $(this).prev("div").attr("id").replace("gallery-", "")
                ) + 1,
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
    });

    $(".delete").click(function () {
        let id = $(this).parents(".col-12").data("pic-id");
        $.ajax({
            url: `/api/pedia/gallery/${id}/delete`,
            type: "GET",
            success(result) {
                alert("刪除成功");
                window.location.reload();
            },
            error(result) {
                alert("刪除失敗");
                console.log(result);
            },
        });
    });
});

function galleryImgUpload() {
    $(".galleryUpload").unbind("change");
    $(".galleryUpload").change(function (e) {
        let file;
        if (e.target.files && e.target.files[0]) {
            file = e.target.files[0];
        } else {
            return false;
        }
        if (file["type"].indexOf("image") >= 0) {
            let url = window.URL.createObjectURL(file),
                id = $(this).parents(".gallery").attr("id");
            $(this).prevAll("img").attr("src", url);
            if (f.has(id)) {
                f.delete(id);
            }
            f.append(id, file);
        } else {
            alert("非圖片格式或格式太前衛");
        }
    });
}

function addGallery() {
    $(".choosePage").unbind("click");
    $(".choosePage").click(function () {
        $(this).siblings().removeClass("show");
        $(this).addClass("show");
        let target = $(this).data("ctrl");
        $(this)
            .parent()
            .next()
            .find(".page[data-target=" + target + "]")
            .addClass("show");
        $(this)
            .parent()
            .next()
            .find(".page[data-target!=" + target + "]")
            .removeClass("show");
        $(this).parent("div").prev("input").val(target);
    });
}

function galleryCreate() {
    let target = $(".gallery"),
        galleries = Array(),
        dataEmpty = false;
    target.each(function () {
        let file = $(this).find(".galleryUpload").val(),
            url = $(this).find(".UrlUpload").val(),
            caption = $(this).find(".textarea").html(),
            main = "";
        if (file == "" && url == "") {
            alert("請選擇上傳檔案或給予連結");
            dataEmpty = true;
            return false;
        }
        if (url == "") {
            main = $(this).attr("id");
        } else {
            main = url;
        }
        if (caption == "") {
            alert("請填寫照片說明");
            dataEmpty = true;
            return false;
        }

        let temp = [main, caption];
        galleries.push(temp);
    });
    if (dataEmpty) {
        return false;
    }
    f.append("galleries", galleries);
    f.append("fatherId", fatherId);
    $.ajax({
        url: `/api/pedia/gallery/create`,
        type: "POST",
        processData: false,
        contentType: false,
        cache: false,
        data: f,
        success(result) {
            if (result["state"] == 1) {
                alert("上傳成功");
                window.location.reload();
            } else {
                console.log(result);
            }
        },
        error(result) {
            console.log(result);
        },
    });
}
