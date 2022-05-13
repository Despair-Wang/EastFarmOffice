$(() => {
    var la = new LoadAnime();
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

    $("#submit").click(function () {
        let name = $("#name").val(),
            email = $("#email").val(),
            tel = $("#tel").val(),
            password = $("#password").val(),
            check = $("#confirmPassword").val();
        if (name == "") {
            alert("請輸入姓名");
            return false;
        }

        if (email == "") {
            alert("請輸入登入用的信箱");
            return false;
        }

        if (password != check) {
            alert("密碼與確認密碼不一致");
            return false;
        }

        la.run();
        $.ajax({
            url: "/api/admin/create",
            type: "POST",
            data: {
                name: name,
                email: email,
                tel: tel,
                password: password,
                password_confirmation: check,
            },
            success(data) {
                la.stop();
                if (data["state"] == 1) {
                    alert("管理者建立成功");
                    reset();
                } else {
                    alert(data["msg"]);
                    console.log(data["data"]);
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

function reset() {
    $("#name").val("");
    $("#email").val("");
    $("#tel").val("");
    $("#password").val("");
    $("#confirmPassword").val("");
}
