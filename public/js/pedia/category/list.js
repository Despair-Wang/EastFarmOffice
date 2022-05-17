var la;
$(() => {
    var md = new MoveDom();
    la = new LoadAnime();
    md.setNew("/pedia/category/edit");
    $(".edit").click(function () {
        let id = $(this).parents(".listBox").attr("id");
        location.href = `/pedia/category/${id}/edit`;
    });

    $(".delete").click(function () {
        let id = $(this).parents(".listBox").attr("id");
        categoryDelete(id);
    });

    $(".postDelete").click(function (e) {
        la.run();
        e.preventDefault();
        let data = new Array(),
            id = $(this).parents(".editPostItem").data("post-id");
        data.push(id);
        $.ajax({
            url: "/api/pedia/category/delete",
            type: "POST",
            data: {
                data: data,
            },
            success(result) {
                la.stop();
                if (result["state"] == 1) {
                    alert("刪除成功");
                    location.reload();
                } else {
                    alert(result["data"]["xdebug_message"]);
                }
            },
            error(data) {
                la.stop();
                alert("CODE_ERROR");
                console.log(data);
            },
        });
    });

    $("#multDeleteButton").click(function () {
        la.run();
        let target = $(".multDelete"),
            data = new Array();
        target.each(function () {
            if ($(this).prop("checked")) {
                let d = $(this).parents(".editPostItem").data("post-id");
                data.push(d);
            }
        });
        la.run();
        $.ajax({
            url: "/api/pedia/category/delete",
            type: "POST",
            data: {
                data: data,
            },
            success(result) {
                la.stop();
                if (result["state"] == 1) {
                    alert("刪除成功");
                    location.reload();
                } else {
                    alert(result["data"]["xdebug_message"]);
                }
            },
            error(data) {
                la.stop();
                alert(data);
            },
        });
    });
});

// function categoryDelete(id) {
//     la.run();
//     $.ajax({
//         url: "/api/pedia/category/delete",
//         type: "POST",
//         data: {
//             id: id,
//         },
//         success(data) {
//             la.stop();
//             if (data["state"] == 1) {
//                 alert("刪除成功");
//                 location.reload();
//             } else {
//                 alert(data["data"]);
//             }
//         },
//         error(data) {
//             la.stop();
//             alert(data);
//         },
//     });
// }
