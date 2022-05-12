<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Coming soon" />
        <title>東鄉事業｜East Farm</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet" />
        <style>
            * {
                padding: 0;
                margin: 0;
                box-sizing: border-box;
            }
            .contact {
                background-color: bisque;
                height: 100vh;
                width: 100vw;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            #logo {
                width: 400px;
                height: auto;
                overflow: hidden;
            }
            img {
                width: 100%;
                height: auto;
            }
            #word {
                margin: 0 auto;
                text-align: center;
            }
            /* #word > p {
            } */
            .animeWords {
                transition: all 0.8s;
                display: inline-block;
                font-size: 2.5rem;
                font-weight: 600;
                color: brown;
                font-family: sans-serif;
            }
        </style>
    </head>
    <body>
        <div class="contact">
            <div class="container">
                <div class="" id="logo"><img src="{{ asset('/assets/source/eastfarmLogo.png') }}" alt="" /></div>
                <div class="" id="word"></div>
            </div>
        </div>
    </body>
    <script>
        $(function () {
            JumpWord('COMING SOON...', 400, 250);
        });

        function JumpWord(word, time, speed) {
            let words = word.split('');
            words.forEach(e => {
                if (e == ' ') {
                    $('#word').append('<p class="animeWords">&nbsp;</p>');
                } else {
                    $('#word').append('<p class="animeWords">' + e + '</p>');
                }
            });
            let count = 0;
            setInterval(() => {
                $('.animeWords').each(function () {
                    if ($(this).html() == '&nbsp;') {
                    } else {
                        setTimeout(() => {
                            $(this).css('transform', 'translateY(-20px)');
                        }, 0 + speed * count);
                        setTimeout(() => {
                            $(this).css('transform', 'translateY(35px)');
                        }, 0 + time + speed * count);
                        setTimeout(() => {
                            $(this).css('transform', 'translateY(0px)');
                        }, 0 + time + 200 + speed * count);
                        count++;
                    }
                });
            }, 1000);
        }
    </script>
</html>
