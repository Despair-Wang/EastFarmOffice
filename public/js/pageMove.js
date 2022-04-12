var createNew, goBack;
class MoveDom {
    constructor() {
        createNew = document.querySelector("#createNew");
        goBack = document.querySelector("#goBack");
    }

    setNew(url) {
        createNew.innerHTML = "<div><div></div><div></div></div><div></div>";
        createNew.addEventListener("click", function () {
            location.href = url;
        });
    }

    setBack(url) {
        goBack.innerHTML = "<div></div><div></div>";
        goBack.addEventListener("click", function () {
            location.href = url;
        });
    }
}
