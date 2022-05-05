$(() => {
    var md = new MoveDom(),
        report = $("#report"),
        la = new LoadAnime();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
        },
    });
    report.hide();
    md.setBack("/order-list");

    $("#closeWindow").click(function () {
        report.hide();
        $("body").removeClass("hiddenScrollY");
    });

    $("#reportBtn").click(function () {
        report.show();
        $("body").addClass("hiddenScrollY");
    });

    $("#submit").click(function () {
        reportPaid();
    });

    $("#cancel").click(function () {
        if (confirm("您是否真的要取消訂單呢？")) {
            la.run();
            let serial = $("#orderDetailBox").data("serial");
            $.ajax({
                url: "/api/order/cancel",
                type: "POST",
                data: {
                    serial: serial,
                },
                success(data) {
                    if (data["state"] == 1) {
                        alert("訂單已經取消了");
                        location.href = "/order-list";
                    } else {
                        la.stop();
                        alert("訂單取消失敗");
                        console.log(data["data"]);
                    }
                },
                error(data) {
                    la.stop();
                    alert("CODE_ERROR" + data);
                    console.log(data);
                },
            });
        }
    });
});

function reportPaid() {
    let serial = $("#orderDetailBox").data("serial"),
        name = $("#name").val(),
        day = $("#day").val(),
        time = $("#time").find(":selected").val(),
        account = $("#account").val(),
        amount = $("#amount").val();
    time = day + " " + time;
    $.ajax({
        url: "/api/order/report",
        type: "POST",
        data: {
            serial: serial,
            name: name,
            time: time,
            account: account,
            amount: amount,
        },
        success(data) {
            if (data["state"] == 1) {
                alert("已通知商家，確認後會盡快出貨");
                location.reload();
            } else {
                alert(data["msg"] + "," + data["data"]);
            }
        },
        error(data) {
            alert(data);
        },
    });
}
