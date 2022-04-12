$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
        },
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
                    console.log(result["data"]["xdebug_message"]);
                }
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
        console.log(data);
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
                    console.log(result["data"]["xdebug_message"]);
                }
            },
        });
    });

    $("#createNew >div").click(() => {
        location.href = "/post/edit";
    });
});
