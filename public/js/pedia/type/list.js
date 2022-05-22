var la;
$(() => {
    var md = new MoveDom(),
        la = new LoadAnime(),
        deleteTags = new Array();
    md.setNew("/pedia/type/edit");

    $(".recover").parents(".editPostItem").addClass("beGray");

    $(".postDelete").click(function (e) {
        e.preventDefault();
        let id = $(this).parents(".editPostItem").data("post-id");
        deleteTags.push(id);
        submit();
    });

    $("#multDeleteButton").click(function () {
        let target = $(".multDelete");
        target.each(function () {
            if ($(this).prop("checked")) {
                let d = $(this).parents(".editPostItem").data("post-id");
                deleteTags.push(d);
            }
        });
        submit();
    });

    $(".freeze").click(function () {
        la.run();
        let id = $(this).parents(".editPostItem").data("post-id");
        $.ajax({
            url: `/api/pedia/type/freeze/${id}`,
            type: "GET",
            success(data) {
                la.stop();
                if (data["state"] == 1) {
                    alert("分類已凍結");
                    location.reload();
                } else {
                    console.log(data["msg"]);
                    console.log(data["data"]);
                }
            },
            error(data) {
                la.stop();
                console.log(data);
            },
        });
    });

    $(".recover").click(function () {
        la.run();
        let id = $(this).parents(".editPostItem").data("post-id");
        $.ajax({
            url: `/api/pedia/type/recover/${id}`,
            type: "GET",
            success(data) {
                la.stop();
                if (data["state"] == 1) {
                    alert("分類已啟用");
                    location.reload();
                } else {
                    console.log(data["msg"]);
                    console.log(data["data"]);
                }
            },
            error(data) {
                la.stop();
                console.log(data);
            },
        });
    });

    function submit() {
        la.run();
        $.ajax({
            url: "/api/pedia/type/delete",
            type: "POST",
            data: {
                data: deleteTags,
            },
            success(result) {
                la.stop();
                if (result["state"] == 1) {
                    alert("刪除成功");
                    location.reload();
                } else {
                    alert("CODE_ERROR");
                    console.log(data);
                }
            },
            error(data) {
                la.stop();
                alert("CODE_ERROR");
                console.log(data);
            },
        });
    }
});
