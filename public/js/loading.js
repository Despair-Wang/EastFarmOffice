var anime, b;
class LoadAnime {
    constructor() {
        $("body").append(`
    <div id="uploadAnime">
        <div>
            <div id="animeCore">
                <div></div>
                <div></div>
                <p>UPLOADING</p>
            </div>
        </div>
    </div>`);
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
