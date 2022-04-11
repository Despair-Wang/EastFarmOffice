<nav class="navbar navbar-dark bg-dark d-md-none">
    <button id="navbarBtn" class="navbar-toggler" type="button">
      <span class="navbar-toggler-icon"></span>
    </button>
</nav>
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
        <a href="{{ url('/o/good-list')}}">產品介紹</a>
        <a class="subDrop dropdown-toggle-split">
            <i class="fa fa-caret-down" aria-hidden="true"></i>
        </a>
        <div class="dropdown-menu">
        @foreach($categories as $key => $good)
            @php
            $key = explode(',',$key);
            $id = $key[0];
            $name = $key[1];
            @endphp
            <div>
                <a class="subDrop-item" href="{{ url('/o/good-list') . '/' . $id }}">{{ $name }}</a>
                @if(count($good) > 0)
                    <a class="subDrop dropdown-button dropdown-toggle-split">
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </a>
                    <div class="subMenu">
                    @foreach ($good as $g)
                        <a class="dropdown-item" href="{{ url('/o/good-list') . '/' . $g['id'] }}">{{ $g['name'] }}</a>
                    @endforeach
                    </div>
                @endif
            </div>
        @endforeach
        </div>
    </div>
    <div class="menuItem">
        <a>聯絡我們</a>
    </div>
    <div class="menuLogin">
        @if (Route::has('login'))
        <div>
            @auth
                <div class="d-flex">
                    <a id="authBtn" class="subDrop dropdown-toggle">Hi, {{ Auth::user()->name }}</a>
                    <div class="subMenu">
                        @if(Auth::user()->Auth == 'admin')
                            <a href="{{ url('/dashboard') }}" class="dropdown-item text-dark">進入後台</a>
                        @else
                            <a href="{{ url('/order-list') }}" class="dropdown-item text-dark">訂單一覽</a>
                            <a href="{{ url('/changePassword') }}" class="dropdown-item text-dark">變更密碼</a>
                            <a href="{{ url('/changeInfo') }}" class="dropdown-item text-dark">變更帳號資訊</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="dropdown-item">
                            @csrf
                            <a class="block text-dark text-dark" href="route('logout')" onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                登出
                            </a>
                        </form>
                    </div>
                </div>
            @else
            <div style="padding-bottom:10px">
                <a href="{{ route('login') }}" class="text-dark text-gray-700 dark:text-gray-500 ">登入</a>
            </div>
            @if (Route::has('register'))
            <div style="padding-top:10px;">
                <a href="{{ route('register') }}" class="text-dark text-gray-700 dark:text-gray-500 ">註冊</a>
            </div>
                @endif
                @endauth
            </div>
            @endif
    </div>
</div>
