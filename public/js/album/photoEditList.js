$(() => {
    let t = $("#fullPhoto");
    t.hide();
    let md = new MoveDom();

    let id = $("#id").val();
    md.setNew(`/album/${id}/photos/edit`);
    md.setBack("/album/list");

    showPhoto();
    $("#submit").click(function () {
        let id = $("#photoId").val(),
            title = $("#photoTitle").val(),
            content = $("#photoContent").val();
        $.ajax({
            url: `/api/album/photo/${id}/edit`,
            type: "POST",
            data: {
                title: title,
                content: content,
            },
            success(result) {
                if (result["state"] == 1) {
                    alert("更新成功");
                    location.href = $(location).attr("href");
                } else {
                    alert("更新失敗 ," + result["data"] + ":" + result["msg"]);
                }
            },
            error(data) {
                alert(data);
            },
        });
    });

    $("#recover").click(function () {
        let id = $("#photoId").val();
        $.ajax({
            url: `/api/album/photo/${id}`,
            type: "GET",
            success(result) {
                if (result["state"] == 1) {
                    setPhoto(result["data"]);
                } else {
                    alert("復原失敗 , " + result["data"] + ":" + result["msg"]);
                }
            },
            error(data) {
                alert(data);
            },
        });
    });

    $("#delete").click(function () {
        let id = $("#photoId").val();
        $.ajax({
            url: `/api/album/photo/delete`,
            type: "POST",
            data: {
                data: id,
            },
            success(result) {
                if (result["state"] == 1) {
                    location.reload();
                } else {
                    alert(
                        "照片刪除失敗 , " + result["data"] + ":" + result["msg"]
                    );
                }
            },
            error(data) {
                alert(data);
            },
        });
    });

    $("#multDelBtn").click(function () {
        $(".multDelete").show();
        $(".photoBox").click(function (e) {
            let input = $(this).find("input");
            if (input.prop("checked")) {
                input.attr("checked", false);
            } else {
                input.attr("checked", true);
            }
        });
        $("#multDelCtrl").show();
    });

    $("#multDelCancel").click(function () {
        $(".multDelete").hide();
        $("#multDelCtrl").hide();
        $(".photoBox").unbind("click");
        $(".multCheck").attr("checked", false);
        showPhoto();
    });

    $("#multDelRun").click(function () {
        let items = $(".multCheck"),
            data = new Array();
        items.each(function (e) {
            if ($(this).prop("checked")) {
                let id = $(this).parent("div").parent("div").data("photo-id");
                data.push(id);
            }
        });
        $.ajax({
            url: "/api/album/photo/delete",
            type: "POST",
            data: {
                data: data,
            },
            success(result) {
                if (result["state"] == 1) {
                    alert("刪除成功");
                    location.reload();
                } else {
                    alert("刪除失敗 , " + result["data"]);
                }
            },
            error(data) {
                alert(data);
            },
        });
    });

    $("#newPhoto").change(function (e) {
        let file = e.target.files[0],
            f = new FormData(),
            id = $("#photoId").val();
        f.append("pic", file);
        $.ajax({
            url: `/api/album/photo/${id}/update`,
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            data: f,
            success(result) {
                $(this).val("");
                if (result["state"] == 1) {
                    t.find("img").attr("src", result["data"]);
                } else {
                    let msg = "";
                    switch (result["msg"]) {
                        case "NO_FILE_UPLOAD":
                            msg = "未傳送檔案";
                            break;
                        case "NOT_SUPPORT_TYPE":
                            msg = "非圖片檔案或檔案格式不支援";
                            break;
                        default:
                            msg = result["msg"];
                            break;
                    }
                    alert(msg);
                }
            },
            error(data) {
                alert(data);
            },
        });
    });

    $("#close").click(function () {
        t.fadeOut();
    });
});

function setPhoto(photo) {
    console.log("done");
    t.find("img").attr("src", photo["url"]);
    $("#photoId").val(photo["id"]);
    $("#photoTitle").val(photo["title"]);
    $("#photoContent").val(photo["content"]);
}

function showPhoto() {
    $(".photoBox").click(function (e) {
        if (
            !$(e.target).hasClass("multDelete") &&
            !$(e.target).hasClass("multCheck")
        ) {
            let id = $(this).data("photo-id");
            $.ajax({
                url: `/api/album/photo/${id}`,
                type: "GET",
                success(result) {
                    if (result["state"] == 1) {
                        setPhoto(result["data"]);
                        t.fadeIn();
                        t.find("").click(function (e) {
                            e.preventDefault();
                            $(this).fadeOut();
                        });
                    } else {
                        alert(result["msg"]);
                    }
                },
                error(data) {
                    alert(data);
                },
            });
        }
    });
}
