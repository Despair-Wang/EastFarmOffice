$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
    },
});
$(() => {
    $("#twzipcode").twzipcode();

    init();

    $(".delete").click(function () {
        let t = $(this).parents(".checkItem"),
            index = t.data("index");
        $.ajax({
            url: "/api/good/cartChange",
            type: "POST",
            data: {
                index: index,
            },
            success(data) {
                if (data["state"] == 1) {
                    if (data["data"] == "reload") {
                        location.reload();
                    } else {
                        t.remove();
                        init();
                    }
                } else {
                    alert(data["msg"] + "," + data["data"]);
                }
            },
            error(data) {
                alert(data);
            },
        });
    });

    $('input[name="payWay"]').on("change", function () {
        if ($(this).val() == "2") {
            $("#addressBox").hide();
        } else {
            $("#addressBox").show();
        }
    });

    $("#buy").click(function () {
        let name = $("#name").val(),
            tel = $("#tel").val(),
            zipcode = $('[name="zipcode"]').val(),
            city = $('select[name="county"]').find(":selected").val(),
            dist = $('select[name="district"]').find(":selected").val(),
            address = $("#address").val(),
            payWay = $('[name="payWay"]:checked'),
            pay = payWay.val(),
            freight = payWay.next().find(".freight").html().replace("$", ""),
            remark = inputFormat($("#remark").html()),
            nothing = $("#nothing").val();

        if (nothing != "true") {
            $.ajax({
                url: "/api/good/order",
                type: "POST",
                data: {
                    name: name,
                    tel: tel,
                    zipcode: zipcode,
                    city: city,
                    dist: dist,
                    address: address,
                    pay: pay,
                    freight: freight,
                    remark: remark,
                },
                success(data) {
                    if (data["state"] == 1) {
                        let serial = data["data"];
                        location.href = `/order/${serial}/complete`;
                    } else {
                        let msg = "訂購失敗" + data["msg"];
                        switch (data["msg"]) {
                            case "NO_NAME":
                                msg = "請輸入收件者姓名";
                                break;
                            case "NO_TEL":
                                msg = "請輸入收件者電話";
                                break;
                            case "NO_ZIPCODE":
                                msg = "請輸入郵遞區號";
                                break;
                            case "NO_ADDRESS":
                            case "NO_CITY":
                            case "NO_DISTRICT":
                                msg = "請輸入完整的收件地址";
                                break;
                        }
                        alert(msg);
                    }
                },
                error(data) {
                    alert(data);
                },
            });
        } else {
            alert("購物車是空的，請先去購物");
        }
    });

    $("#remark").bind("paste", function (e) {
        e.preventDefault();
        let old = $(this).html();
        let t = e.originalEvent.clipboardData.getData("text");
        $(this).html(old + t);
    });

    $("#continue").click(function () {
        location.href = "/o/good-list";
    });

    function init() {
        var total = 0,
            count = 0;
        $(".checkItem").each(function () {
            let t = $(this),
                number = parseInt(t.find(".number").html().replace("X", "")),
                price = parseInt(t.find(".price").html().replace("$ ", ""));
            temp = number * price;
            total += temp;
            // t[0].setAttribute('data-index',count);
            t.find(".sum").html("$ " + temp);
            count++;
        });
        $("#total").html("Total " + total);
    }

    function inputFormat(content) {
        let start = /<div>/g,
            end = /<\/div>/g;
        content = content.replace("<div>", "<br>");
        content = content.replace(/<div>/g, "");
        content = content.replace(/<\/div>/g, "<br>");
        return content;
    }
});
