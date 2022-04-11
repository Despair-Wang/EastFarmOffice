$(() => {
    var md = new MoveDom();
    md.setNew("/post/tag/edit");

    $(".postDelete").click(function (e) {
        e.preventDefault();
        let id = $(this).parents(".editPostItem").data("post-id");
        $.ajax({
            url: "/api/post/tag/deleteCheck",
            type: "POST",
            data: {
                data: id,
            },
            success(result) {
                if (result["state"] == 1) {
                    let posts = "",
                        post = result["data"]["result"];
                    result["data"]["result"].forEach((e) => {
                        posts += "《" + e["title"] + "》\n";
                    });
                    let answer = confirm(
                        `刪除此分類，將會影響到${result["data"]["count"]}篇文章\n對象如下：\n${posts}是否要繼續刪除？`
                    );
                    if (answer) {
                        $.ajax({
                            url: "/api/post/tag/delete",
                            type: "POST",
                            data: {
                                data: id,
                            },
                            success(result) {
                                if (result["state"] == 1) {
                                    alert(
                                        `操作結束，刪除了${result["data"]["success"]}項標籤，失敗${result["data"]["false"]}項`
                                    );
                                    location.href = "/post/tag/list";
                                } else {
                                    alert(result["data"]["xdebug_message"]);
                                }
                            },
                            error(data) {
                                alert(data);
                            },
                        });
                    }

                    // alert(
                    //     `操作結束，刪除了${result["data"]["success"]}項標籤，失敗${result["data"]["false"]}項`
                    // );
                    // location.href = "/post/tag/list";
                } else {
                    alert(result["data"]);
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
        $.ajax({
            url: "/api/post/tag/deleteCheck",
            type: "POST",
            data: {
                data: data,
            },
            success(result) {
                if (result["state"] == 1) {
                    let posts = "",
                        post = result["data"]["result"];
                    result["data"]["result"].forEach((e) => {
                        posts += "《" + e["title"] + "》\n";
                    });
                    let answer = confirm(
                        `刪除此分類，將會影響到${result["data"]["count"]}篇文章\n對象如下：\n${posts}是否要繼續刪除？`
                    );
                    if (answer) {
                        $.ajax({
                            url: "/api/post/tag/delete",
                            type: "POST",
                            data: {
                                data: data,
                            },
                            success(result) {
                                if (result["state"] == 1) {
                                    alert(
                                        `操作結束，刪除了${result["data"]["success"]}項標籤，失敗${result["data"]["false"]}項`
                                    );
                                    location.href = "/post/tag/list";
                                } else {
                                    alert(result["data"]["xdebug_message"]);
                                }
                            },
                        });
                    }
                } else {
                    alert(result["data"]);
                }
            },
        });
    });
});
