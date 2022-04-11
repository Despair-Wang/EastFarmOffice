<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('layouts.basicHead')
    <script src="{{ asset('js/lc_switch.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/backend')}}"></script>
    <link rel="stylesheet" href="{{ asset('/css/backend.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lc_switch.css') }}">
    @yield('customJs')
    <title>東鄉事業官網後台-@yield('title')</title>
</head>
<body>
    <div class="" id="menu">
        @include('layouts.backendMenu')
    </div>
    <div id="content">
        <h1 class="h1">
            @yield('h1')
        </h1>
        <div class="container" id="container">
            @yield('content')
        </div>
        <div class="" id="footer">
            @include('layouts.footer')
        </div>
    </div>
</body>
@yield('customJsBottom')
</html>
