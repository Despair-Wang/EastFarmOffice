var f = new FormData(),
    deleteList = new Array();
$(() => {
    var md = new MoveDom(),
        id = $("#itemId").val();
    if (id == "") {
        md.setBack(`/pedia/list/`);
    } else {
        md.setBack(`/pedia/${id}/preview/`);
    }

    tagInit();

    $("#tag").on("change", () => {
        addTag();
    });

    // $("#image").change(function (e) {
    //     let file = e.target.files[0];
    //     if (file["type"].indexOf("image") >= 0) {
    //         let url = window.URL.createObjectURL(file);
    //         $(this).parent("div").find("img").attr("src", url);
    //         if (f.has("image")) {
    //             f.delete("image");
    //         }
    //         f.append("image", file);
    //     } else {
    //         alert("非圖片格式或格式太前衛");
    //     }
    // });

    $("#createItem").click(function () {
        createItem();
    });

    $("#deleteImage").click(function () {
        f.delete("image");
        f.delete("oldImage");
        $("#oldImage").val("");
        $("#image").val("");
        $("#image").parent("div").find("img").attr("src", "");
    });
});

function tagInit() {
    let taglist = $("#tag > option"),
        t = $(".tag");
    // console.log(t);
    for (let i = 0; i < t.length; i++) {
        for (let j = 0; j < taglist.length; j++) {
            if (taglist[j].value == t[i].dataset.tagId) {
                taglist[j].remove();
            }
        }
    }
    $(".removeTag").click(function (e) {
        removeTag(e.target);
    });
}

function createItem() {
    let name = $("#name").val(),
        category = $("#category").find(":selected").val(),
        id = $("#itemId").val(),
        oldImage = $("#oldImage").val(),
        tags = new Array();
    (tag = $("#addedTag").find(".tag")), (url = "");
    tag.each(function () {
        tags.push($(this).data("tagId"));
    });
    f.append("name", name);
    f.append("category", category);
    f.append("image", $("#coverUpload").val());
    f.append("oldImage", oldImage);
    f.append("tags", JSON.stringify(tags));
    f.append("deleteList", JSON.stringify(deleteList));
    if (id == "") {
        url = "/api/pedia/create";
    } else {
        url = `/api/pedia/${id}/update`;
    }
    $.ajax({
        url: url,
        type: "POST",
        processData: false,
        contentType: false,
        cache: false,
        data: f,
        success(result) {
            if (result["state"] == 1) {
                alert("建立成功");
                let id = result["data"];
                location.href = `/pedia/${id}/preview`;
            } else {
                switch (result["msg"]) {
                    case "PEDIA_NAME_IS_EXISTS":
                        alert("該百科項目名稱已經存在，請前往編輯現有項目");
                        break;
                    case "CREATE_ERROR":
                        alert("百科項目建立失敗");
                        break;
                    case "UPDATE_ERROR":
                        alert("百科項目更新失敗");
                        break;
                }
                console.log(result["msg"]);
                console.log(result["data"]);
            }
        },
        error(result) {
            console.log(result);
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
        `<div class="tagBox"><div class="tag" data-tagId="${tag.val()}">${tag.text()}</div><a class="removeTag">X</a></div>`
    );
    tag.remove();
    $("#tag").find('option[value="-"]').prop("selected", "true");
    let x = $(".removeTag");
    x.off();
    x.on("click", function (e) {
        // console.log(e);
        removeTag(e.target);
    });
}

function removeTag(target) {
    console.log("click");
    let tb = $(target).parent("div"),
        t = tb.children(".tag"),
        id = t.data("tagId");
    $("#tag").append(
        `<option value="${id}">${t.text().replace("X", "")}</option>`
    );
    tb.remove();
    deleteList.push(id);
}
