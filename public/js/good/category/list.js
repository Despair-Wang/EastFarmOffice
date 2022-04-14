var la;
$(() => {
    var md = new MoveDom();
    la = new LoadAnime();
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
    la.run();
    $.ajax({
        url: "/api/good/category/delete",
        type: "POST",
        data: {
            id: id,
        },
        success(data) {
            la.stop();
            if (data["state"] == 1) {
                alert("刪除成功");
                location.reload();
            } else {
                alert(data["data"]);
            }
        },
        error(data) {
            la.stop();
            alert(data);
        },
    });
}
