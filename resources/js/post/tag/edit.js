$(() => {
    $("#submit").click(function () {
        let name = $("#tagName").val(),
            content = $("#tagContent").val(),
            id = $("#tagId").val(),
            url = "";
        if ($("#action").val() == "update") {
            url = `/api/post/tag/${id}/update`;
        } else {
            url = `/api/post/tag/create`;
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
                    location.href = "/post/tag/list";
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
