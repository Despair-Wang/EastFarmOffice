$(() => {
    let id = $("#id").val(),
        md = new MoveDom();
    md.setBack("/pedia/list");
    $("#editItem").click(function () {
        location.href = `/pedia/item/${id}/edit`;
    });

    $("#addContent").click(function () {
        let sort = $(".pediaContent").length + 1;
        location.href = `/pedia/content/${id}/edit?sort=${sort}`;
    });

    $("#addGallery").click(function () {
        location.href = `/pedia/gallery/${id}/edit`;
    });

    $("#complete").click(function () {
        location.href = "/pedia/list";
    });

    $("#delete").click(function () {
        $.ajax({
            url: `/api/pedia/${id}/delete`,
            type: "GET",
            success(data) {
                if (data["state"] == 1) {
                    alert("項目撤銷成功");
                    location.href = "/pedia/list";
                } else {
                    alert("撤銷失敗");
                    console.log(data["data"]);
                }
            },
            error(data) {
                alert("CODE_ERROR");
                console.log(data);
            },
        });
    });

    $("#recover").click(function () {
        $.ajax({
            url: `/api/pedia/${id}/recover`,
            type: "GET",
            success(data) {
                if (data["state"] == 1) {
                    alert("項目復原成功");
                    location.href = "/pedia/list";
                } else {
                    alert("復原失敗");
                    console.log(data["data"]);
                }
            },
            error(data) {
                alert("CODE_ERROR");
                console.log(data);
            },
        });
    });
});
