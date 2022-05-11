var la;
$(() => {
    var md = new MoveDom(),
        la = new LoadAnime(),
        deleteTags = new Array();
    md.setNew("/good/tag/edit");

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

    function submit() {
        la.run();
        $.ajax({
            url: "/api/good/tag/delete",
            type: "POST",
            data: {
                deleteList: deleteTags,
            },
            success(result) {
                la.stop();
                if (result["state"] == 1) {
                    alert("刪除成功");
                    location.href = "/good/tag/list";
                } else {
                    alert(result["data"]["xdebug_message"]);
                }
            },
            error(data) {
                la.stop();
                alert(data);
            },
        });
    }
});
