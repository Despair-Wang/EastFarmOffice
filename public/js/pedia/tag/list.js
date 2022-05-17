var la;
$(() => {
    var md = new MoveDom(),
        la = new LoadAnime(),
        deleteTags = new Array();
    md.setNew("/pedia/tag/edit");

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
            url: "/api/pedia/tag/delete",
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
