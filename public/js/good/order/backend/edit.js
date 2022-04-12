var deleteType = new Array(),
    serial = $("#orderDetailBox").data("serial");
$(() => {
    let md = new MoveDom();
    md.setBack("/good/order/list");
    $(".typeDel").click(function () {
        let t = $(this).parents(".typeBox"),
            id = t.attr("id");
        deleteType.push(id);
        t.remove();
    });

    $("#sum").click(function () {
        let total = 0;
        $(".typeBox").each(function () {
            let t = $(this),
                a = parseInt(t.find(".amount").val()),
                q = parseInt(t.find(".quantity").val());
            total += a * q;
        });
        let f = parseInt($("#freight").val());
        total += f;
        $("#total").val(total);
    });

    $("#submit").click(function () {
        let state = $("#state").find(":selected").val(),
            name = $("#name").val(),
            address = $("#address").val(),
            types = new Array(),
            freight = $("#freight").val(),
            total = $("#total").val(),
            payment = $("#payment").find(":selected").val();
        $(".typeBox").each(function () {
            let id = $(this).attr("id"),
                a = $(this).find(".amount").val(),
                q = $(this).find(".quantity").val();
            types.push([id, a, q]);
        });

        $.ajax({
            url: `/api/good/order/${serial}/edit`,
            type: "POST",
            data: {
                state: state,
                name: name,
                address: address,
                types: types,
                freight: freight,
                total: total,
                pay: payment,
                deleteType: deleteType,
            },
            success(data) {
                if (data["state"] == 1) {
                    alert("訂單修改完成");
                    location.href = `/good/order/${serial}/`;
                } else {
                    alert(data["msg"] + "," + data["data"]);
                }
            },
            error(data) {
                alert(data);
            },
        });
    });

    $("#reset").click(function () {
        location.reload();
    });
});
