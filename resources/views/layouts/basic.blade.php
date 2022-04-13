<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('layouts.basicHead')
    <link rel="stylesheet" href="{{ asset('css/basic.css') }}">
    @yield('customJs')
    <title>{{ env("APP_NAME") }}-@yield('title')</title>
</head>
<body>
    <div id="content">
        <div id="top">
            @include('layouts.menu')
        </div>
        <div id="container" class="container">
            <h1 class="h1">@yield('h1')</h1>
            {{-- @include('menu') --}}
            @yield('content')
        </div>
    </div>
    <div id="footer">
        @include('layouts.footer')
    </div>
</body>
@yield('customJsBottom')

</html>
