var md = new MoveDom();
$(() => {
    md.setNew("/good/create");

    $(".putUp").parents(".goodListBox").addClass("beGray");

    $(".edit").click(function () {
        let id = $(this).parents(".goodListBox").data("good-id");
        location.href = `/good/${id}/edit`;
    });

    $(".stock").click(function () {
        let id = $(this).parents(".goodListBox").data("good-id");
        location.href = `/good/${id}/stock`;
    });

    $(".putdown").click(function () {
        let id = $(this).parents(".goodListBox").data("good-id");
        $.ajax({
            url: `/api/good/${id}/putdown`,
            type: "GET",
            success(data) {
                if (data["state"] == 1) {
                    alert("商品已下架");
                    location.reload();
                } else {
                    console.log(data["msg"]);
                    console.log(data["data"]);
                }
            },
            error(data) {
                console.log(data);
            },
        });
    });

    $(".putUp").click(function () {
        let id = $(this).parents(".goodListBox").data("good-id");
        $.ajax({
            url: `/api/good/${id}/putUp`,
            type: "GET",
            success(data) {
                if (data["state"] == 1) {
                    alert("商品已上架");
                    location.reload();
                } else {
                    console.log(data["msg"]);
                    console.log(data["data"]);
                }
            },
            error(data) {
                console.log(data);
            },
        });
    });

    $(".delete").click(function () {
        if (confirm("是否真的要刪除商品？刪除後將無法復原")) {
            let id = $(this).parents(".goodListBox").data("good-id");
            $.ajax({
                url: `/api/good/${id}/delete`,
                type: "GET",
                success(data) {
                    if (data["state"] == 1) {
                        alert("商品已刪除");
                        location.reload();
                    } else {
                        console.log(data["msg"]);
                        console.log(data["data"]);
                    }
                },
                error(data) {
                    console.log(data);
                },
            });
        }
    });
});
