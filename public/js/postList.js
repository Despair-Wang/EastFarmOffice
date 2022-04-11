$(() => {
    let url = location.href,
        star = url.indexOf("list/"),
        haveType = url.indexOf("type");
    type = "";

    type = url.split("list/")[1];
    if (star > 0) {
        let end = type.indexOf("?");
        if (end < 0) {
            end = type.length;
        }
        type = type.slice(0, end);
    }
    $("#type").find(`[value="${type}"]`).attr("selected", true);

    $("#type").change(function () {
        let target = $(this).val();
        location.href = "/post/list/" + target;
    });

    $(".postDelete").click(function (e) {
        e.preventDefault();
        let id = $(this).parents(".editPostItem").data("post-id");
        $.ajax({
            url: "/api/post/post/delete",
            type: "POST",
            data: {
                data: id,
            },
            success(result) {
                if (result["state"] == 1) {
                    alert(
                        `操作結束，刪除了${result["data"]["success"]}篇文章，失敗${result["data"]["false"]}篇`
                    );
                    location.href = "/post/list";
                } else {
                    alert(result["data"]["xdebug_message"]);
                }
            },
            error(data) {
                alert(data);
            },
        });
    });

    $("#multDeleteButton").click(function () {
        let target = $(".multDelete"),
            data = new Array();
        target.each(function () {
            if ($(this).prop("checked")) {
                let d = $(this).parents(".editPostItem").data("post-id");
                data.push(d);
            }
        });
        $.ajax({
            url: "/api/post/post/delete",
            type: "POST",
            data: {
                data: data,
            },
            success(result) {
                if (result["state"] == 1) {
                    alert(
                        `操作結束，刪除了${result["data"]["success"]}篇文章，失敗${result["data"]["false"]}篇`
                    );
                    location.href = "/post/list";
                } else {
                    alert(result["data"]["xdebug_message"]);
                }
            },
            error(data) {
                alert(data);
            },
        });
    });

    $("#createNew >div").click(() => {
        location.href = "/post/edit";
    });
});
