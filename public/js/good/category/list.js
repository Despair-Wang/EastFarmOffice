$(() => {
    var md = new MoveDom();
    md.setNew("/good/category/create");
    $(".edit").click(function () {
        let id = $(this).parents(".listBox").attr("id");
        location.href = `/good/category/${id}/edit`;
    });

    $(".delete").click(function () {
        let id = $(this).parents(".listBox").attr("id");
        categoryDelete(id);
    });
});

function categoryDelete(id) {
    $.ajax({
        url: "/api/good/category/delete",
        type: "POST",
        data: {
            id: id,
        },
        success: function (data) {
            if (data["state"] == 1) {
                alert("刪除成功");
                location.reload();
            } else {
                alert(data["data"]);
            }
        },
        error: function (data) {
            alert(data);
        },
    });
}
