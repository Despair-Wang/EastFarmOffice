$(() => {
    $(".showPassword").click(function () {
        let t = $(this).prev("input");
        if (t.hasClass("show")) {
            t.attr("type", "password");
            t.removeClass("show");
            $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
        } else {
            t.attr("type", "text");
            t.addClass("show");
            $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
        }
    });
});
