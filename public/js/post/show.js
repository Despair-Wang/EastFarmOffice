$(() => {
    var md = new MoveDom();
    md.setBack("/o/post-list");
    let id = $("#post").data("postid");
    $("#rewrite").click(function () {
        console.log("click");
        location.href = `/post/${id}/edit/rewrite`;
    });

    $(".tagBox.show").click(function () {
        let d = $(this).data("tag-id");
        location.href = "/o/post/tag/" + d;
    });
});
