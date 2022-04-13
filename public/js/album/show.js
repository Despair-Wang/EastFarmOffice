$(() => {
    var md = new MoveDom();
    md.setBack("/o/album-list");
    let t = $("#fullPhoto"),
        b = $("body");
    $(".photoBox").click(function () {
        let id = $(this).data("photo-id");
        $.ajax({
            url: `/api/album/photo/${id}`,
            type: "GET",
            success(result) {
                if (result["state"] == 1) {
                    t.find("img").attr("src", result["data"]["url"]);
                    t.find("h4").html(result["data"]["title"]);
                    t.find("h6").html(result["data"]["content"]);
                    // t.addClass('show');
                    t.fadeIn();
                    b.addClass("hiddenScrollY");
                    t.click(function (e) {
                        e.preventDefault();
                        $(this).fadeOut();
                        b.removeClass("hiddenScrollY");
                    });
                } else {
                    alert(result["msg"]);
                }
            },
        });
    });
});
