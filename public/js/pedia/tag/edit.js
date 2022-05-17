$(() => {
    var md = new MoveDom(),
        la = new LoadAnime();
    md.setBack("/pedia/tag/list");
    $("#submit").click(function () {
        la.run();
        let name = $("#tagName").val(),
            type = $("#tagType > option:selected").val(),
            id = $("#tagId").val(),
            url = "";
        if ($("#action").val() == "update") {
            url = `/api/pedia/tag/${id}/update`;
        } else {
            url = `/api/pedia/tag/create`;
        }
        $.ajax({
            url: url,
            type: "POST",
            data: {
                name: name,
                typeId: type,
            },
            success(result) {
                la.stop();
                if (result["state"] == 1) {
                    alert("建立/更新成功");
                    location.href = "/pedia/tag/list";
                } else {
                    alert("操作失敗 , " + result["data"]);
                }
            },
            error(data) {
                la.stop();
                alert(data);
            },
        });
    });

    $("#reset").click(function () {
        $("#name").val("");
        $("#content").val("");
    });
});
