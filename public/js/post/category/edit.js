$(() => {
    var md = new MoveDom(),
        la = new LoadAnime();
    md.setBack("/post/category/list");
    $("#submit").click(function () {
        let name = $("#cateName").val(),
            content = $("#cateContent").val(),
            id = $("#cateId").val(),
            url = "";
        if ($("#action").val() == "update") {
            url = `/api/post/category/${id}/update`;
        } else {
            url = `/api/post/category/create`;
        }
        $.ajax({
            url: url,
            type: "post",
            data: {
                name: name,
                content: content,
            },
            success(result) {
                if (result["state"] == 1) {
                    alert("建立/更新成功");
                    location.href = "/post/category/list";
                } else {
                    alert("操作失敗 , " + result["data"]);
                }
            },
            error(data) {
                alert(data);
            },
        });
    });

    $("#reset").click(function () {
        $("#name").val("");
        $("#content").val("");
    });
});
