$(() => {
    $("#filter").click(function () {
        let start = $("#start").val(),
            end = $("#end").val(),
            page = $("#page").find(":selected").val(),
            state = $("#state").find(":selected").val();
        if (start == "") {
            start = null;
        }
        if (end == "") {
            end = null;
        }

        if (start == null && end != null) {
            alert("請選擇開始範圍");
        } else if (start != null && end == null) {
            alert("請選擇結束範圍");
        } else if (start != null && end != null) {
            if (end >= start) {
                location.href = `/good/order/list/${start}/${end}/${page}/${state}`;
            } else {
                alert("結束日期不可小於開始日期");
            }
        } else {
            location.href = `/good/order/list/${start}/${end}/${page}/${state}`;
        }
    });
});
