$(() => {
    var md = new MoveDom(),
        albumId = $("#id").val(),
        anime = $("#uploadAnime");

    md.setBack(`/album/${albumId}/photos`);

    let area = $("#uploadArea")[0];
    area.ondragover = function (e) {
        e.preventDefault();
    };

    area.ondrop = function (e) {
        startRun();
        e.preventDefault();
        let data = e.dataTransfer.files,
            f = new FormData();
        for (let i = 0; i < data.length; i++) {
            f.append("pic" + i, data[i]);
        }
        f.append("id", albumId);
        $.ajax({
            url: "/api/album/uploadImg",
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            data: f,
            success(result) {
                endRun();
                if (result["state"] == 1) {
                    result = result["data"];
                    for (let i = 0; i < result.length; i++) {
                        $("#photoShowArea").append(
                            '<div class="col-6 col-lg-3 p-2"><img src="' +
                                result[i] +
                                '" class="img-fluid"></div>'
                        );
                    }
                } else {
                    alert(
                        "上傳圖片失敗：" + result["msg"] + "," + result["data"]
                    );
                }
            },
            error(data) {
                endRun();
                alert(data);
            },
        });
    };

    $("#upload").change(function (e) {
        startRun();
        let f = new FormData();
        f.append("pic", e.target.files[0]);
        f.append("id", $("#id").val());
        $.ajax({
            url: "/api/album/uploadImg",
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            data: f,
            success(result) {
                endRun();
                if (result["state"] == 1) {
                    result = result["data"];
                    for (let i = 0; i < result.length; i++) {
                        $("#photoShowArea").append(
                            '<div class="col-6 col-lg-3 p-2"><img src="' +
                                result[i] +
                                '" class="img-fluid"></div>'
                        );
                    }
                    e.target.value = "";
                } else {
                    alert(
                        "上傳圖片失敗：" + result["msg"] + "," + result["data"]
                    );
                }
            },
            error(data) {
                endRun();
                alert(data);
            },
        });
    });

    function startRun() {
        anime.fadeIn();
        $("body").addClass("hiddenScrollY");
    }

    function endRun() {
        anime.fadeOut();
        $("body").removeClass("hiddenScrollY");
    }
});
