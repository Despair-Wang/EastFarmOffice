var anime, b;
class LoadAnime {
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
