$(() => {
    $(".subDrop").click(function () {
        let t = $(this).next();
        if (t.hasClass("show")) {
            t.removeClass("show");
        } else {
            t.addClass("show");
        }
    });

    $("#navbarBtn").click(function () {
        let t = $("#menuBox");
        if (t.hasClass("show")) {
            t.removeClass("show");
        } else {
            t.addClass("show");
        }
    });
});
