$(() => {
    init();

    $(".filter > input").change(function () {
        $(this)
            .parent()
            .find(".btn-success")
            .toggleClass("btn-success btn-outline-success");
        $(this).next().toggleClass("btn-outline-success btn-success");
        filter();
    });

    $(".pediaItem").click(function () {
        let name = $(this).data("name");
        location.href = `/o/pedia/${name}`;
    });
});

function filter() {
    let filter = new Array();
    $(".filter").each(function () {
        let value = $(this).find("input:checked").val();
        filter.push(value);
    });
    $('input[name="filter"]').val(filter);
    $("#filterForm").submit();
}

function init() {
    let filter = $('input[name="filter"]').val();
    if (!(filter == "")) {
        filter = filter.split(",");
        filter.forEach((e) => {
            let t;
            if (e.substr(0, 3) == "all") {
                t = $(`#type${e.substr(4)}`);
            } else {
                t = $(`#tag${e}`);
            }
            t.attr("checked", true);
            t.parent()
                .find(".btn-success")
                .toggleClass("btn-success btn-outline-success");
            t.next().toggleClass("btn-outline-success btn-success");
        });
    } else {
        $(".filter > label:first-of-type").toggleClass(
            "btn-outline-success btn-success"
        );
    }
}
