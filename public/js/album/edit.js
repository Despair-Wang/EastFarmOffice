var f = new FormData(),
    la;
$(() => {
    var md = new MoveDom();
    la = new LoadAnime();
    md.setBack("/album/list");

    $("#cover").change(function (e) {
        var file = e.target.files[0];
        if (file["type"].indexOf("image") >= 0) {
            let url = window.URL.createObjectURL(file);
            $("#showCover").children("img").attr("src", url);
            if (f.has("pic")) {
                f.delete("pic");
            }
            f.append("pic", file);
        } else {
            alert("非圖片格式或格式太前衛");
        }
    });

    $("#reset").click(function () {
        $("#albumName").val("");
        $("#albumContent").val("");
    });

    $("#submit").click(function () {
        la.run();
        let name = $("#albumName").val(),
            content = $("#albumContent").val(),
            action = $("#action").val(),
            id = $("#id").val(),
            url = "";
        f.append("name", name);
        f.append("content", content);
        if (action == "update") {
            url = `/api/album/${id}/edit`;
        } else {
            url = "/api/album/create";
        }
        $.ajax({
            url: url,
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            data: f,
            success(result) {
                la.stop();
                if (result["state"] == 1) {
                    if (action == "update") {
                        alert("相簿更新完成");
                        location.href = `/album/${result["data"]["id"]}/photos/`;
                    } else {
                        alert("相簿建立完成");
                        location.href = `/album/${result["data"]["id"]}/photos/edit`;
                    }
                } else {
                    alert(result["data"] + "," + result["msg"]);
                }
            },
            error(data) {
                la.stop();
                alert(data);
            },
        });
    });

    $("#delete").click(function () {
        la.run();
        let id = $("#id").val();
        $.ajax({
            url: `/api/album/${id}/delete`,
            type: "GET",
            success(result) {
                la.stop();
                if (result["state"] == 1) {
                    alert("相簿刪除成功");
                    location.href = "/album/list";
                } else {
                    alert("刪除失敗 , " + result["data"]);
                }
            },
            error(data) {
                la.stop();
                alert(data);
            },
        });
    });
});
