$(() => {
    var md = new MoveDom();
    md.setBack("/o/good-list");
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
});
