var f = new FormData(),
    galleries = new Array(),
    deleteType = new Array(),
    deleteGallery = new Array(),
    tags = new Array(),
    deleteTags = new Array(),
    la;
$(() => {
    var md = new MoveDom();
    la = new LoadAnime();
    deleteTypeInit();
    deleteInit();
    let tagList = $("#tag > option"),
        exist = $(".tag");
    for (let i = 0; i < exist.length; i++) {
        for (let j = 0; j < tagList.length; j++) {
            if ($(exist[i]).data("tag-id") == $(tagList[j]).val()) {
                $(tagList[j]).remove();
                break;
            }
        }
    }
    removeTagInit();
    md.setBack("/good/list");
    $("#hot").lc_switch("開啟", "關閉");

    let area = $("#uploadArea")[0];
    area.ondragover = function (e) {
        e.preventDefault();
    };

    area.ondrop = function (e) {
        e.preventDefault();
        let data = e.dataTransfer.files;
        for (let i = 0; i < data.length; i++) {
            if (data[i].type.indexOf("image") >= 0) {
                let url = window.URL.createObjectURL(data[i]),
                    name = data[i].name,
                    img = `
                        <div class="col-6 col-md-3 mb-3 img" data-name="${name}">
                            <div>
                                <i class="fa fa-times del" aria-hidden="true"></i>
                                <img src="${url}">
                            </div>
                        </div>`;
                $("#photoShowArea").append(img);
                f.append(name, data[i]);
                galleries.push(name);
            } else {
                alert("非圖片格式或格式太前衛");
            }
        }
        deleteInit();
    };

    $("#upload").change(function (e) {
        var file;
        if (e.target.files || e.target.files[0]) {
            file = e.target.files[0];
        }
        if (file["type"].indexOf("image") >= 0) {
            let name = file["name"],
                url = window.URL.createObjectURL(file),
                img = `
                        <div class="col-6 col-md-3 mb-3 img" data-name="${name}">
                            <div>
                                <i class="fa fa-times del" aria-hidden="true"></i>
                                <img src="${url}">
                            </div>
                        </div>`;
            $("#photoShowArea").append(img);
            f.append(name, file);
            galleries.push(name);
            deleteInit();
        } else {
            alert("非圖片格式或格式太前衛");
        }
    });

    $("#addType").click(function () {
        addType();
    });

    $("#tag").on("change", function () {
        addTag();
    });

    $("#submit").click(function () {
        submit();
    });

    $("#caption").bind("paste", function (e) {
        e.preventDefault();
        let old = $(this).html();
        let t = e.originalEvent.clipboardData.getData("text");
        $(this).html(old + t);
    });
});

function inputFormat(content) {
    let start = /<div>/g,
        end = /<\/div>/g;
    content = content.replace("<div>", "<br>");
    content = content.replace(/<div>/g, "");
    content = content.replace(/<\/div>/g, "<br>");
    return content;
}

function deleteInit() {
    $(".del").unbind("click");
    $(".del").bind("click", function () {
        let target = $(this).parents(".img"),
            name = target.data("name"),
            index = galleries.indexOf(name),
            url = target.find("img").attr("src");
        galleries.splice(index, 1);
        if (f.has(name)) {
            f.delete(name);
        }
        deleteGallery.push(url);
        target.remove();
    });
}

function deleteTypeInit() {
    $(".typeDel").unbind("click");
    $(".typeDel").bind("click", function () {
        let t = $(this).parents(".typeBox"),
            id = t.attr("id");
        t.remove();
        deleteType.push(id);
    });
}

function addType() {
    let t = $(".typeBox"),
        count = 1;
    if (t.length > 0) {
        count =
            parseInt(
                $(t[t.length - 1])
                    .attr("id")
                    .replace("type", "")
            ) + 1;
    }
    // console.log(count);
    let html = `
            <div class="typeBox" id="type${count}">
                <i class="fa fa-times float-right typeDel curP" aria-hidden="true"></i>
                <label>款式名稱</label>
                <input type="text" class="typeName" value="">
                <label>款式說明</label>
                <input type="text" class="typeDescription">
                <label>單價</label>
                <input type="number" class="price" value="0">
                <label>庫存數量</label>
                <input type="number" class="quantity" value="0">
            </div>`;
    $("#addType").prev().append(html);
    deleteTypeInit();
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
    tags.push(tag.val());
    removeTagInit();
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
    deleteTags.push(t.data("tag-id"));
}

function removeTagInit() {
    let x = $(".removeTag");
    x.off();
    x.on("click", function (e) {
        removeTag(e.target);
    });
}

function submit() {
    let id = $("#id").val(),
        name = $("#name").val(),
        cover = $("#coverUpload").val(),
        category = $("#category").find(":selected").val(),
        caption = inputFormat($("#caption").html()),
        typeList = new Array(),
        hot = "",
        url = "",
        error = false,
        typeCount = 1;

    if ($("#hot").next().hasClass("lcs_on")) {
        hot = 1;
    } else {
        hot = 0;
    }

    $(".typeBox").each(function () {
        let name = $(this).find(".typeName").val(),
            description = $(this).find(".typeDescription").val(),
            quantity = $(this).find(".quantity").val(),
            price = $(this).find(".price").val(),
            typeId = $(this).attr("id");
        if (name == "") {
            alert("請輸入樣式名稱");
            error = true;
        }
        if (price == "") {
            alert("請設定價格");
            error = true;
        } else if (price <= 0) {
            alert("價格不可小於或等於0");
            error = true;
        }
        if (quantity == "") {
            alert("請輸入庫存量");
            error = true;
        } else if (quantity <= 0) {
            alert("庫存量不可小於或等於0");
            error = true;
        }
        if (description == "") {
            description = "暫無說明";
        }
        if (error == true) {
            return false;
        }
        f.append(typeId, [typeCount, name, description, price, quantity]);
        typeList.push(typeId);
        typeCount++;
    });
    if (error == true) {
        return false;
    }
    f.append("name", name);
    f.append("cover", cover);
    f.append("category", category);
    f.append("caption", caption);
    f.append("typeList", typeList);
    f.append("galleries", galleries);
    f.append("hot", hot);
    f.append("deleteType", deleteType);
    f.append("deleteGallery", deleteGallery);
    f.append("tags", tags);
    f.append("deleteTags", deleteTags);
    if (id == "") {
        url = "/api/good/create";
    } else {
        url = `/api/good/${id}/update`;
    }
    la.run();
    $.ajax({
        url: url,
        type: "POST",
        processData: false,
        contentType: false,
        cache: false,
        data: f,
        success: function (data) {
            la.stop();
            if (data["state"] == 1) {
                alert("商品建立成功");
                location.href = `/good/list`;
            } else {
                console.log(data["msg"]);
                console.log(data["data"]);
            }
        },
        error: function (data) {
            la.stop();
            console.log(data);
        },
    });
}
