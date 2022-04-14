const serial = $("#orderDetailBox").data("serial");
var la;
$(() => {
    let md = new MoveDom();
    la = new LoadAnime();
    md.setBack("/good/order/list");
    $("#edit").click(function () {
        location.href = `/good/order/${serial}/edit`;
    });

    $("#paid").click(function () {
        changeState("paid");
    });

    $("#delivered").click(function () {
        changeState("delivered");
    });

    $("#cancel").click(function () {
        changeState("cancel");
    });
});

function changeState(state) {
    la.run();
    let url = "";
    switch (state) {
        case "paid":
            url = `/api/good/order/${serial}/paid`;
            break;
        case "delivered":
            url = `/api/good/order/${serial}/delivered`;
            break;
        case "cancel":
            url = `/api/good/order/${serial}/cancel`;
            break;
    }
    $.ajax({
        url: url,
        type: "GET",
        success(data) {
            la.stop();
            if (data["state"] == 1) {
                alert("狀態變更成功");
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
