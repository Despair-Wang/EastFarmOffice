$(() => {
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

        $.ajax({
            url: "/api/changeInfo",
            type: "POST",
            data: {
                name: name,
                email: email,
            },
            success(data) {
                if (data["state"] == 1) {
                    alert("資訊已更新");
                    location.href = "/dashboard";
                } else {
                    alert(data["msg"] + "," + data["data"]);
                }
            },
            error(data) {
                alert("Code Error," + data["responseJSON"]["message"]);
                console.log(data);
            },
        });
    });
});
