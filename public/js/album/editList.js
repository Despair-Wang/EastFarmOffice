$(() => {
    $.ajax({
        url: "/api/album/getList/admin",
        type: "GET",
        success(result) {
            let t = $("#albumMenu");
            Object.keys(result).forEach(function (e) {
                temp = `<div><ul><li class="yearItem">${e}年</li><ul>`;
                Object.keys(result[e]).forEach(function (m) {
                    temp += `<li><a href="/album/list/${e}/${m}">${m}月(${result[e][m]})</a></li>`;
                });
                temp += "</ul></ul></div>";
                t.append(temp);
            });
            setMenuAction();
        },
    });

    var md = new MoveDom();
    md.setNew("/album/edit");

    $(".albumListItem").click(function (e) {
        let t = $(e.target);
        if (
            !t.hasClass("edit") &&
            !t.hasClass("upload") &&
            !t.hasClass("delete")
        ) {
            let id = $(this).children("div").data("album-id");
            location.href = `/album/${id}/photos/`;
        }
    });

    $(".edit").click(function (e) {
        e.preventDefault();
        let id = $(this).parent("div").data("album-id");
        location.href = `/album/${id}/edit`;
    });

    $(".upload").click(function (e) {
        e.preventDefault();
        let id = $(this).parent("div").data("album-id");
        location.href = `/album/${id}/photos/edit`;
    });

    $(".showPhoto").click(function (e) {
        e.preventDefault();
        let id = $(this).parent("div").data("album-id");
        location.href = `/album/${id}/photos`;
    });

    $(".delete").click(function (e) {
        e.preventDefault();
        let id = $(this).parent("div").data("album-id");
        $.ajax({
            url: `/api/album/${id}/delete`,
            type: "GET",
            success(result) {
                if (result["state"] == 1) {
                    alert("相簿刪除成功");
                    location.href = "/album/list";
                } else {
                    alert("刪除失敗 , " + result["data"]);
                }
            },
            error(data) {
                alert(data);
            },
        });
    });
});

function setMenuAction() {
    $(".yearItem").click(function (e) {
        let t = $(this).next("ul");
        if (t.height() == 0) {
            $(this).addClass("open");
            let height = t.find("li").height(),
                len = t.find("li").length;
            t.css("height", height * len);
        } else {
            $(this).removeClass("open");
            t.css("height", 0);
        }
    });
}
