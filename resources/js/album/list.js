$(() => {
    $.ajax({
        url: "/api/getAlbumList",
        type: "GET",
        success(result) {
            let t = $("#albumMenu");
            Object.keys(result).forEach(function (e) {
                temp = `<div><ul><li class="yearItem">${e}年</li><ul>`;
                Object.keys(result[e]).forEach(function (m) {
                    temp += `<li><a href="/o/album-list/${e}/${m}">${m}月(${result[e][m]})</a></li>`;
                });
                temp += "</ul></ul></div>";
                t.append(temp);
            });
            setMenuAction();
        },
    });

    $(".albumListItem").click(function (e) {
        let id = $(this).children("div").data("album-id");
        location.href = `/o/album/${id}/photos/`;
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
