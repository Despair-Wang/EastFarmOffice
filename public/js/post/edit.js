var la;
$(() => {
    var md = new MoveDom();
    md.setBack("/post/list");
    la = new LoadAnime();
    const E = window.wangEditor;
    const e = new E("#mainInput");
    e.config.uploadImgServer = "/api/post/upload";
    e.config.uploadFileName = "pic";
    e.config.uploadImgHeaders = {
        "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
    };
    e.create();
    $("#tag").on("change", () => {
        addTag();
    });

    $("#submit").click(() => {
        createPost("normal");
    });

    $("#draft").click(() => {
        createPost("draft");
    });

    let up = document.querySelector("#uploadArea");
    up.ondragover = function (e) {
        e.preventDefault();
    };

    up.ondrop = function (e) {
        e.preventDefault();
        uploadImg(e.dataTransfer.files[0]);
    };

    $("#imgUpload").on("change", function () {
        let data = this.files[0];
        if (data.type.indexOf("image") == 0) {
            uploadImg(data);
        } else {
            alert("請上傳圖片或圖片格式過於前衛無法支援");
        }
    });

    $("#reset").click(function () {
        $("#title").val("");
        $(".w-e-text").html("");
        $(".removeTag").each(function () {
            removeTag($(this));
        });
        $("#category").find(":selected").attr("selected", false);
        $("#indexImage").attr("src", "");
        $("#imgUpload").val("");
    });

    var postId = $("#action").data("post-id");
    if (postId != "" && typeof postId != "undefined") {
        getContent(postId);
    }
});

function uploadImg(file) {
    la.run();
    let f = new FormData();
    f.append("pic", file);
    $.ajax({
        url: "/api/post/upload",
        type: "post",
        processData: false,
        contentType: false,
        cache: false,
        data: f,
        success(result) {
            la.stop();
            result = JSON.parse(result);
            if (result["errno"] == 0) {
                $("#indexImage").attr("src", result["data"][0]["url"]);
            } else {
                alert("圖片上傳失敗, " + result["data"][0]["url"]);
            }
        },
        error(data) {
            la.stop();
            alert("圖片上傳失敗, " + data);
        },
    });
}

function getContent(id) {
    la.run();
    $.ajax({
        url: `/api/post/${id}/getContent`,
        type: "get",
        success(result) {
            la.stop();
            if (result["state"] == 1) {
                let data = result["data"],
                    taglist = $("#tag > option"),
                    t = data["tags"];
                $(".w-e-text").html(data["content"]);
                for (let i = 0; i < t.length; i++) {
                    for (let j = 0; j < taglist.length; j++) {
                        if (taglist[j].value == t[i]) {
                            taglist[j].remove();
                        }
                    }
                }
                $(".removeTag").click(function (e) {
                    removeTag(e.target);
                });
            } else {
                la.stop();
                alert(result["data"]);
            }
        },
        error(data) {
            la.stop();
            alert(data);
        },
    });
}

function addTag() {
    let tag = $("#tag").find(":selected");
    if (tag.val() == "-") {
        alert("請選擇一個有效的標籤");
        return false;
    }
    $("#addedTag").append(
        `<div class="tagBox"><div class="tag" data-tag-id="${tag.val()}">${tag.text()}</div><a class="removeTag">X</a></div>`
    );
    tag.remove();
    $("#tag").find('option[value="-"]').prop("selected", "true");
    let x = $(".removeTag");
    x.off();
    x.on("click", function (e) {
        removeTag(e.target);
    });
}

function removeTag(target) {
    let tb = $(target).parent("div"),
        t = tb.children(".tag");
    $("#tag").append(
        `<option value="${t.data("tag-id")}">${t
            .text()
            .replace("X", "")}</option>`
    );
    tb.remove();
}

function createPost(saveType) {
    la.run();
    let title = $("#title").val(),
        content = $(".w-e-text").html(),
        cate = $("#category").find(":selected").val(),
        postId = $("#action").data("post-id"),
        version = $("#version").val(),
        tagTemp = $(".tag"),
        tags = new Array(),
        picTemp = $(".w-e-text").find("img"),
        pics = new Array(),
        url = "",
        image = $("#indexImage").attr("src");
    picTemp.each(function () {
        pics.push($(this).attr("src"));
    });
    tagTemp.each(function () {
        tags.push($(this).data("tag-id"));
    });
    switch ($("#action").data("action")) {
        case "rewrite":
            url = "/api/post/create/rewrite";
            break;
        case "update":
            url = "/api/post/create/update";
            break;
        default:
            url = "/api/post/create";
            break;
    }
    $.ajax({
        url: url,
        type: "post",
        data: {
            title: title,
            content: content,
            category: cate,
            postId: postId,
            version: version,
            tags: tags,
            pics: pics,
            image: image,
            save: saveType,
        },
        success(result) {
            la.stop();
            if (result["state"] == "1") {
                // alert('文章建立成功');
                if (saveType == "draft") {
                    alert("草稿建立完成");
                    location.href = "/post/list";
                } else {
                    location.href =
                        "/post/" + result["data"]["id"] + "/preview";
                }
            } else {
                alert("文章建立失敗:" + result["msg"] + "," + result["data"]);
            }
        },
        error(data) {
            la.stop();
            alert(data);
        },
    });
}
