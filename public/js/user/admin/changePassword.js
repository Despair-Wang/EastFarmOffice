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
        let password = $("#password").val(),
            check = $("#confirmPassword").val();

        if (password != check) {
            alert("密碼與確認密碼不一致");
            return false;
        }
        la.run();
        $.ajax({
            url: "/api/changePassword",
            type: "POST",
            data: {
                password: password,
                password_confirmation: check,
            },
            success(data) {
                la.stop();
                if (data["state"] == 1) {
                    alert("密碼已更新");
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
