$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
    },
});

$(() => {
    let id = $("#post").data("postid");
    $("#rewrite").click(function () {
        console.log("click");
        location.href = `/post/${id}/edit/rewrite`;
    });

    $(".tagBox.show").click(function () {
        let d = $(this).data("tag-id");
        location.href = "/o/post/tag/" + d;
    });

    $("#complete").click(function () {
        $.ajax({
            url: `/api/post/${id}/beComplete`,
            type: "get",
            success(result) {
                if (result["state"] == "1") {
                    let post = result["data"]["id"];
                    alert("發佈成功");
                    location.href = "/post/list";
                } else {
                    alert("發佈失敗 ," + result["data"]);
                }
            },
            error(data) {
                alert("data");
            },
        });
    });
});
