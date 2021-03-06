$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
    },
});
$(() => {
    $("#twzipcode").twzipcode();
    var la = new LoadAnime();
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

    $("#useUserInfo").change(function () {
        if ($(this).prop("checked")) {
            $.ajax({
                url: "/api/getUserInfo",
                type: "GET",
                success(data) {
                    if (data["state"] == 1) {
                        $("#name").val(data["data"]["name"]);
                        $("#tel").val(data["data"]["tel"]);
                    } else {
                        alert(data["msg"] + "," + data["data"]);
                    }
                },
                error(data) {
                    alert(data);
                },
            });
        } else {
            $("#name").val("");
            $("#tel").val("");
        }
    });

    let t = $("#commonlyUsed");

    $("#selectAddress").click(function () {
        t.data("target", "main");
        addressInit();
        t.fadeIn();
    });

    $("#selectSubAddress").click(function () {
        t.data("target", "sub");
        addressInit();
        t.fadeIn();
    });

    $("#closeAddress").click(function () {
        t.fadeOut();
    });

    $("#addAddress").click(function () {
        $("#addressKeyIn").show();
    });

    $("#another").change(function () {
        if ($(this).prop("checked")) {
            $("#anotherAddress").show();
        } else {
            $("#anotherAddress").hide();
        }
    });

    $("#addressSubmit").click(function () {
        la.run();
        let city = $('select[name="county"]').find(":selected").val(),
            district = $('select[name="district"]').find(":selected").val(),
            zipcode = $('input[name="zipcode"]').val(),
            address = city + district + $("#address").val();
        $.ajax({
            url: "/api/addAddress",
            type: "POST",
            data: {
                zipcode: zipcode,
                address: address,
            },
            success(data) {
                la.stop();
                if (data["state"] == 1) {
                    $("#addressKeyIn").hide();
                    addressInit();
                }
            },
            error(data) {
                la.stop();
                alert("ERROR,PLEASE TO SEE THE CONSOLE");
                console.log(data);
            },
        });
    });

    $("#useAddress").click(function () {
        let selected = $('input[name="address"]:checked'),
            target = $("#commonlyUsed").data("target");
        if (target != "null") {
            if (selected.length > 0) {
                let zipcode = selected.next().find(".zipcode").html(),
                    address = selected.next().find(".address").html();
                switch (target) {
                    case "main":
                        $("#addressShow > h6").html(zipcode + " " + address);
                        $("#zipcodeInput").val(zipcode);
                        $("#addressInput").val(address);
                        break;
                    case "sub":
                        $("#subAddress").html(zipcode + " " + address);
                        $("#subZipcodeInput").val(zipcode);
                        $("#subAddressInput").val(address);
                        break;
                }

                t.fadeOut();
            } else {
                alert("????????????????????????????????????????????????");
            }
        }
    });

    $('input[name="payWay"]').on("change", function () {
        if ($(this).val() == "2") {
            $("#addressBox").hide();
            $("#sendInvoice").hide();
        } else {
            $("#addressBox").show();
            $("#sendInvoice").show();
        }
    });

    $('input[name="invoiceType"]').on("change", function () {
        if ($(this).val() == "donate") {
            $("#sendInvoice").hide();
        } else {
            $("#sendInvoice").show();
        }
    });

    $("#buy").click(function () {
        la.run();
        let name = $("#name").val(),
            tel = $("#tel").val(),
            zipcode = $("#zipcodeInput").val(),
            address = $("#addressInput").val(),
            payWay = $('[name="payWay"]:checked'),
            pay = payWay.val(),
            freight = payWay.next().find(".freight").html().replace("$", ""),
            remark = inputFormat($("#remark").html()),
            invoiceType = $('[name="invoiceType"]:checked').val(),
            taxNumber = $("#taxNumber").val(),
            invoiceSendType = $('[name="sendType"]:checked').val(),
            invoiceZipcode = $("#subZipcodeInput").val(),
            invoiceAddress = $("#subAddressInput").val(),
            nothing = $("#nothing").val();

        if (nothing != "true") {
            $.ajax({
                url: "/api/good/order",
                type: "POST",
                data: {
                    name: name,
                    tel: tel,
                    zipcode: zipcode,
                    address: address,
                    pay: pay,
                    invoiceType: invoiceType,
                    taxNumber: taxNumber,
                    invoiceSendType: invoiceSendType,
                    invoiceZipcode: invoiceZipcode,
                    invoiceAddress: invoiceAddress,
                    freight: freight,
                    remark: remark,
                },
                success(data) {
                    if (data["state"] == 1) {
                        let serial = data["data"];
                        location.href = `/order/${serial}/complete`;
                    } else {
                        la.stop();
                        let msg = "????????????" + data["msg"];
                        switch (data["msg"]) {
                            case "NO_NAME":
                                msg = "????????????????????????";
                                break;
                            case "NO_TEL":
                                msg = "????????????????????????";
                                break;
                            case "NO_ZIPCODE":
                                msg = "?????????????????????";
                                break;
                            case "NO_ADDRESS":
                                msg = "???????????????????????????";
                                break;
                            case "STOCK_IS_ZERO":
                                msg = data["data"] + "??????????????????0???";
                                break;
                            case "MORE_THAN_STOCK":
                                msg =
                                    data["data"] +
                                    "???????????????????????????????????????????????????";
                                break;
                        }
                        alert(msg);
                        console.log(data["data"]);
                    }
                },
                error(data) {
                    la.stop();
                    alert("ERROR,PLEASE TO SEE THE CONSOLE");
                    console.log(data);
                },
            });
        } else {
            la.stop();
            alert("????????????????????????????????????");
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

    function addressInit() {
        let list = $("#addressList");
        list.html("");
        $.ajax({
            url: "/api/getAddress",
            type: "GET",
            success(data) {
                if (data["state"] == 1) {
                    let addresses = data["data"];
                    addresses.forEach(function (a) {
                        let id = a["id"],
                            zipcode = a["zipcode"],
                            address = a["address"];
                        temp = `<div class="col-12">
                                    <input id="${id}" class="mr-3" type="radio" name="address">
                                    <label class="curP w-100" for="${id}">
                                        <h6 class="h6 zipcode">${zipcode}</h6>
                                        <h6 class="h6 address">${address}</h6>
                                    </label>
                                    <i class="fa fa-times curP deleteAddress" aria-hidden="true"></i>
                                </div>`;
                        list.append(temp);
                    });
                    removeAddress();
                }
            },
            error(data) {
                alert("ERROR,PLEASE TO SEE THE CONSOLE");
                console.log(data);
            },
        });
    }

    function removeAddress() {
        $(".deleteAddress").unbind("click");
        $(".deleteAddress").click(function () {
            if (confirm("?????????????????????????????????")) {
                let id = $(this).prev().prev().attr("id");
                $.ajax({
                    url: `/api/removeAddress/${id}`,
                    type: "GET",
                    success(data) {
                        if (data["state"] == 1) {
                            addressInit();
                        }
                    },
                    error(data) {
                        alert("ERROR,PLEASE TO SEE THE CONSOLE");
                        console.log(data);
                    },
                });
            }
        });
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
