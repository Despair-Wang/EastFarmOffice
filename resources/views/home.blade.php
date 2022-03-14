<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('layouts.basicHead')
    <link rel="stylesheet" href="{{ asset('css/basic.css') }}">
    <title>{{ env('APP_NAME') }}-台灣首座國際級茶花公園</title>
    <style>
        #topImage {
            background-image:
            linear-gradient(to bottom,rgba(255,255,255,0.7) 0%, rgba(0,0,0,0) 20%),
            url('/storage/source/577697.jpg');
            background-size: cover;
            height: 100vh;
            width: 100%;
            margin-top: -100px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #topImage > div{
            text-align: center;
            height: min-content;
            text-shadow: 0 0 2px #000;
        }

        #topTitle {
            font-weight: 900;
            color: white;
            font-size: 5rem;
        }

        #topSubtitle {
            font-weight: 600;
            color: aliceblue;
            font-size: 2.5rem;

        }

    </style>
</head>

<body>
    <div id="content">
        <div id="top">
            @include('layouts.menu')
        </div>
        <div id="container">
            <div id="topImage">
                <div>
                    <p id="topTitle">東鄉事業</p>
                    <p id="topSubtitle">台灣首座國際級茶花公園</p>
                </div>
            </div>
        </div>
        <div id="footer">
            @include('layouts.footer')
        </div>
    </div>
</body>

</html>
