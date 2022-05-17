$(() => {
    var md = new MoveDom(),
        la = new LoadAnime();
    md.setBack("/pedia/type/list");
    $("#submit").click(function () {
        la.run();
        let name = $("#typeName").val(),
            id = $("#typeId").val(),
            url = "";
        if ($("#action").val() == "update") {
            url = `/api/pedia/type/${id}/update`;
        } else {
            url = `/api/pedia/type/create`;
        }
        $.ajax({
            url: url,
            type: "POST",
            data: {
                name: name,
            },
            success(result) {
                la.stop();
                if (result["state"] == 1) {
                    alert("建立/更新成功");
                    location.href = "/pedia/type/list";
                } else {
                    alert("操作失敗 , " + result["data"]);
                    console.log(result["data"]);
                }
            },
            error(data) {
                la.stop();
                alert("CODE_ERROR");
                console.log(data);
            },
        });
    });

    $("#reset").click(function () {
        $("#name").val("");
        $("#content").val("");
    });
});
