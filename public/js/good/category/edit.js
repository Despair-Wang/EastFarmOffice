$(() => {
    var md = new MoveDom(),
        la = new LoadAnime();
    md.setBack("/good/category/list");
    $("#submit").click(function () {
        la.run();
        let id = $("#id").val(),
            name = $("#name").val(),
            sub = $("#sub").find(":selected").val(),
            content = $("#cateContent").val(),
            url = "";
        if (typeof id == "undefined") {
            url = "/api/good/category/create";
        } else {
            url = `/api/good/category/${id}/update`;
        }
        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: id,
                name: name,
                sub: sub,
                content: content,
            },
            success(result) {
                la.stop();
                if (result["state"] == 1) {
                    alert("建立成功");
                    location.href = "/good/category/list";
                } else {
                    alert("建立失敗 , " + result["data"]);
                }
            },
            error(result) {
                la.stop();
                alert(result);
                console.log(result);
            },
        });
    });

    $("#reset").click(function () {
        reset();
    });
});

function reset() {
    let id = $("#id").val(""),
        name = $("#name").val(""),
        sub = $("#sub").find('option[value="-"]').attr("selected", true),
        content = $("#content").val("");
}
