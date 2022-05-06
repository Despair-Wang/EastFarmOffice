$(() => {
    var md = new MoveDom(),
        favorite = $("#favorite"),
        notice = $("#favoriteNotice");
    md.setBack("/o/good-list");
    notice.slideUp();

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

    favorite.click(function () {
        let state = $(this).hasClass("fa-heart-o"),
            goodId = $("input[name='id']").val();
        if (state) {
            $.ajax({
                url: "/api/addFavorites",
                type: "POST",
                data: {
                    goodId: goodId,
                },
                success(data) {
                    if (data == false) {
                        alert("登入後即可使用加入最愛的功能");
                        location.href = "/login";
                    } else {
                        if (data["state"] == 1) {
                            // alert("已成功加入最愛商品");
                            changeHeart("on");
                        } else {
                            if (data["msg"] == "EXIST") {
                                alert("商品已經加入過了。");
                            }
                        }
                    }
                },
                error(data) {
                    alert("CODE_ERROR");
                    console.log(data);
                },
            });
        } else {
            $.ajax({
                url: "/api/removeFavorites",
                type: "POST",
                data: {
                    goodId: goodId,
                },
                success(data) {
                    if (data == false) {
                        alert("登入後即可使用加入最愛的功能");
                        location.href = "/login";
                    } else {
                        if (data["state"] == 1) {
                            // alert("已取消為最愛商品");
                            changeHeart("off");
                        }
                    }
                },
                error(data) {
                    alert("CODE_ERROR");
                    console.log(data);
                },
            });
        }
    });

    function changeHeart(state) {
        switch (state) {
            case "on":
                favorite.addClass("heartBeBig");
                notice.html("商品已加入最愛！");
                notice.slideDown();
                favorite.removeClass("fa-heart-o");
                favorite.addClass("text-danger");
                favorite.addClass("fa-heart");
                setTimeout(() => {
                    notice.slideUp();
                }, 2000);
                break;
            case "off":
                notice.html("已取消為最愛商品");
                notice.slideDown();
                favorite.removeClass("fa-heart");
                favorite.removeClass("heartBeBig");
                favorite.removeClass("text-danger");
                favorite.addClass("fa-heart-o");
                setTimeout(() => {
                    notice.slideUp();
                }, 2000);
                break;
        }
    }
});
