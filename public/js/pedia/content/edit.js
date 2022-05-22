var f = new FormData(),
    la,
    deleteImage = new Array();
$(() => {
    var md = new MoveDom(),
        fatherId = $("#fatherId").val();
    la = new LoadAnime();
    addContentImg();
    useRemark();
    deleteInit();
    md.setBack(`/pedia/${fatherId}/preview`);

    $("#addRemark").click(function () {
        let t = $("#remarkBox");
        if (t.height() == 0) {
            t.addClass("show");
        } else {
            t.removeClass("show");
        }
    });

    $("#createRemark").click(function () {
        let content = $(this).parent().find(".remarkContent"),
            url = $(this).parent().find(".remarkUrl"),
            sort = $("#remarkInputBox > ul").find("li").length + 1,
            href = "";
        if (url.val() != "") {
            href = `href="${url.val()}"`;
        }
        $("#remarkInputBox > ul").append(`
            <li data-sort="${sort}"><a ${href}>[※${sort}]${content.val()}</a></li>
            `);
        content.val("");
        url.val("");
    });

    $("#contents").bind("paste", function (e) {
        e.preventDefault();
        let old = $(this).html();
        let t = e.originalEvent.clipboardData.getData("text");
        if (old == "" || old == " ") {
            $(this).html(t);
        } else {
            $(this).html(old + t);
        }
    });

    $("#submit").click(function () {
        createContent();
    });
});

function inputFormat(content) {
    let start = /<div>/g,
        end = /<\/div>/g;
    content = content.replace("<div>", "<br>");
    content = content.replace(/<div>/g, "");
    content = content.replace(/<\/div>/g, "<br>");
    content = content.replace(" ", "");
    return content;
}

function useRemark() {
    $(".useRemark").unbind("click");
    $(".useRemark").click(function () {
        let t = $("#addRemarkBox"),
            list = $("#remarkSelect");
        if (t.width() == 0) {
            list.html("");
            let remarks = $("#remarkInputBox > ul").find("li");
            if (remarks.length > 0) {
                remarks.each(function (e) {
                    let re = $(this);
                    list.append(
                        `<option value="${re.data(
                            "sort"
                        )}">${re.html()}</option>`
                    );
                });
                insertRemark();
            } else {
                list.append('<option value="-">無註釋</option>');
            }
            t.addClass("show");
        }
        // else {
        //     t.removeClass("show");
        // }
    });
}

function insertRemark() {
    $(".insertRemark").unbind("click");
    $(".insertRemark").click(function () {
        var insertT, range, text;
        if (window.getSelection) {
            insertT = window.getSelection();
            if (insertT.getRangeAt && insertT.rangeCount) {
                let t = $(this).prev().find(":selected").val(),
                    text = `[※${t}]`;
                range = insertT.getRangeAt(0);
                if (
                    $(range["startContainer"])[0]["parentElement"].getAttribute(
                        "id"
                    ) == "contents"
                ) {
                    range.insertNode(document.createTextNode(text));
                    insertT.collapseToEnd();
                } else {
                    return false;
                }
                $("#addRemarkBox").removeClass("show");
            }
        }
    });
}

function addContentImg() {
    $(".addImage").unbind("click");
    $(".addImage").click(function (e) {
        e.preventDefault();
        let id = $(this).parents(".c-Box").attr("id"),
            exists = $("#imageBox").find(".c-Image"),
            subId = 1;
        if (exists.length > 0) {
            subId =
                parseInt(
                    exists[exists.length - 1]
                        .getAttribute("id")
                        .replace("cImg", "")
                ) + 1;
        }
        let item = `
            <div id="cImg${subId}" class="c-Image col-6 col-md-3">
                <i class="fa fa-times float-right del curP" aria-hidden="true"></i>
                <div>
                    <img>
                </div>
                <input type="file" class="c-ImgUpload">
                <h5 class="h5 mt-2">說明文字</h5>
                <input type="text" class="imgMessage">
            </div>`;
        $(this).parent().before(item);
        contentImgUpload();
        deleteInit();
    });
}

function contentImgUpload() {
    $(".c-ImgUpload").unbind("change");
    $(".c-ImgUpload").change(function (e) {
        let file = e.target.files[0];
        if (file["type"].indexOf("image") >= 0) {
            let id = $(this).parent(".c-Image").attr("id"),
                url = window.URL.createObjectURL(file);
            $(this).prev("div").children("img").attr("src", url);
            if (f.has(id)) {
                f.delete(id);
            }
            f.append(id, file);
        } else {
            alert("非圖片格式或格式太前衛");
        }
    });
}

function deleteInit() {
    $(".del").off();
    $(".del").on("click", function () {
        let t = $(this).parent(".c-Image"),
            index = t.attr("id");
        deleteImage.push(index);
        t.remove();
    });
}

function createContent() {
    la.run();
    let fatherId = $("#fatherId").val(),
        id = $("#c-Box").data("content-id"),
        sort = $("#c-Box").data("sort"),
        title = $("#title").val(),
        content = $("#contents").html(),
        gContent = $(".imgMessage"),
        remark = $(".remarkInputBox > ul > li"),
        remarks = Array(),
        active = $("#active").val(),
        url = "";
    f.append("deleteImage", deleteImage);
    f.append("itemId", fatherId);
    f.append("sort", sort);
    f.append("title", title);
    f.append("content", content);
    gContent.each(function () {
        f.append($(this).parent().attr("id") + "c", $(this).val());
    });
    remark.each(function () {
        // f.append('re' + $(this).data('sort') , $(this).html());
        remarks.push($(this).html());
    });
    f.append("remarks", remarks);
    if (active == "update") {
        url = `/api/pedia/content/${id}/update`;
    } else {
        url = "/api/pedia/content/create";
    }
    $.ajax({
        url: url,
        type: "POST",
        processData: false,
        contentType: false,
        cache: false,
        data: f,
        success: function (result) {
            la.stop();
            if (result["state"] == 1) {
                alert("建立成功");
                location.href = `/pedia/${fatherId}/preview`;
            } else {
                alert(result["msg"]);
                console.log(result["data"]);
            }
        },
        error: function (result) {
            alert("CODE_ERROR");
            console.log(result);
        },
    });
}
