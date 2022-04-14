$(() => {
    var la = new LoadAnime();
    $("#submit").click(function () {
        let name = $("#name").val(),
            email = $("#email").val();
        if (name == "") {
            alert("請輸入姓名");
            return false;
        }

        if (email == "") {
            alert("請輸入電子郵件");
            return false;
        }
        la.run();
        $.ajax({
            url: "/api/changeInfo",
            type: "POST",
            data: {
                name: name,
                email: email,
            },
            success(data) {
                la.stop();
                if (data["state"] == 1) {
                    alert("資訊已更新");
                    location.href = "/dashboard";
                } else {
                    alert(data["msg"] + "," + data["data"]);
                }
            },
            error(data) {
                la.stop();
                alert("Code Error," + data["responseJSON"]["message"]);
                console.log(data);
            },
        });
    });
});
