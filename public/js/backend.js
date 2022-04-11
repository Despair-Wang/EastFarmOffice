$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
    },
});

$(() => {
    $(".mainItem").click(function (e) {
        let t = $(this).next(".subItem"),
            h = t.children("div").height();

        if (t.css("height") == "0px") {
            t.height(h - 2);
        } else {
            t.height(0);
        }
    });
});
