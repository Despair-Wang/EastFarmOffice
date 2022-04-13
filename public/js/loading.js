var anime, b;
class loadAnime {
    constructor() {
        anime = $("#uploadAnime");
        b = $("body");
    }

    run() {
        anime.fadeIn();
        b.addClass("hiddenScrollY");
    }

    stop() {
        anime.fadeOut();
        b.removeClass("hiddenScrollY");
    }
}
