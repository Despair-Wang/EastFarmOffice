$(() => {
    var md = new MoveDom();
    md.setBack("/o/good-list");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
        },
    });

    $("#ctrlRight")
        .children("i")
        .click(function () {
            let now = $("#mediaBox").find(".onTop"),
                next = now.next();
            if (typeof next[0] == "undefined") {
                next = $("#mediaBox > img:first-child");
            }
            next.addClass("onTop");
            now.removeClass("onTop");
        });

    $("#ctrlLeft")
        .children("i")
        .click(function () {
            let now = $("#mediaBox").find(".onTop"),
                next = now.prev();
            if (typeof next[0] == "undefined") {
                next = $("#mediaBox > img:last-child");
            }
            next.addClass("onTop");
            now.removeClass("onTop");
        });

    $("#restockNotice").click(function (e) {
        e.preventDefault();
        let goodId = $("input[name='id']").val();
        $.ajax({
            url: "/api/restockNotice",
            type: "POST",
            data: {
                goodId: goodId,
            },
            success(data) {
                if (data["state"] == 1) {
                    alert("感謝您的關注，當我們進貨時，會再通知您");
                } else {
                    alert(data["msg"]);
                    console.log(data["data"]);
                }
            },
            error(data) {
                alert("CODE ERROR");
                console.log(data);
            },
        });
    });
});
