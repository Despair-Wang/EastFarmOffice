$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
    },
});
var md = new MoveDom(),
    id = $("#typeSelect").data("good-id"),
    serial = $("#typeSelect").data("good-serial");
$(() => {
    md.setBack("/o/good-list");

    $(".addNum").click(function () {
        let t = $(this).prev().find("input"),
            number = t.val();
        number++;
        t.val(number);
    });

    $(".reduceNum").click(function () {
        let t = $(this).next().find("input"),
            number = t.val();
        if (number <= 0) {
            number = 0;
        } else {
            number--;
        }
        t.val(number);
    });

    $("#submit").click(function () {
        order();
    });

    $("#cart").click(function () {
        location.href = "/good/orderCheck";
    });
});

function order() {
    let orders = new Array(),
        types = $(".selectItem");

    types.each(function () {
        let type = $(this).data("type"),
            number = $(this).find(".order").val();
        orders.push([type, number]);
    });

    $.ajax({
        url: "/api/good/addCart",
        type: "POST",
        data: {
            id: id,
            orders: orders,
        },
        success(data) {
            if (data["state"] == 1) {
                alert("商品已經加入購物車");
                // closeWindow()
                location.href = "/o/good-list";
            } else {
                alert(data["msg"] + " , " + data["data"]);
            }
        },
        error(data) {
            alert(data);
        },
    });
}
