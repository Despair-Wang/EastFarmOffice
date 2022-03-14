<div id="menuBox">
    <div class="menuItem">
        <a href="/">首頁</a>
    </div>
    <div class="menuItem">
        <a>關於東鄉</a>
    </div>
    <div class="menuItem">
        <a href="{{ url('/o/post-list') }}">茶花文選</a>
    </div>
    <div class="menuItem">
        <a>茶花百科</a>
    </div>
    <div class="menuItem">
        <a href="{{ url('/o/album-list') }}">茶花千景</a>
    </div>
    <div class="menuItem">
        <a>產品介紹</a>
    </div>
    <div class="menuItem">
        <a>聯絡我們</a>
    </div>
    <div class="menuLogin">
        @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">進入後台</a>
            @else
            <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">註冊</a>
                @endif
                @endauth
            </div>
            @endif
    </div>
</div>
