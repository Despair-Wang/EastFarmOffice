var la;
$(() => {
    la = new LoadAnime();
    var md = new MoveDom();
    $(".io").lc_switch("進貨", "出貨");

    md.setBack("/good/list");

    $("#submit").click(function () {
        submit();
    });
});

function submit() {
    la.run();
    types = new Array();
    $(".typeBox").each(function () {
        let t = $(this),
            goodId = t.data("good-id"),
            type = t.data("type"),
            number = t.find(".ioControl").val(),
            action = t.find(".io").prop("checked");
        types.push([goodId, type, number, action]);
    });
    la.stop();
    $.ajax({
        url: "/api/good/stockChange",
        type: "POST",
        data: {
            types: types,
        },
        success(data) {
            if (data["state"] == 1) {
                alert("庫存調整完畢");
                location.reload();
            } else {
                alert(data["msg"] + "," + data["data"]);
            }
        },
        error(data) {
            la.stop();
            alert(data);
        },
    });
}
