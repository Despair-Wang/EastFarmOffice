<div id="menuBox">
    <div class="welcomeBar">
        <p>Hello,{{ Auth::user()->name }}</p>
    </div>
    <div class="menuItem">
        <a href="{{ route('home') }}" class="mainItem">前台首頁</a>
    </div>
    <div class="menuItem">
        <a class="mainItem">文章管理</a>
        <div class="subItem">
            <div>
                <a href="/post/list">文章一覽</a>
                <a href="/post/category/list">分類一覽</a>
                <a href="/post/tag/list">標籤一覽</a>
            </div>
        </div>
    </div>
    <div class="menuItem">
        <a class="mainItem">百科管理</a>
        <div class="subItem">
            <div>
                <a href="/pedia/list">項目一覽</a>
                <a href="/pedia/category/list">分類一覽</a>
                <a href="/pedia/tag/list">屬性一覽</a>
                <a href="/pedia/type/list">屬性分類一覽</a>
            </div>
        </div>
    </div>
    <div class="menuItem">
        <a class="mainItem">相簿管理</a>
        <div class="subItem">
            <div>
                <a href="/album/list">相簿一覽</a>
            </div>
        </div>
    </div>
    <div class="menuItem">
        <a class="mainItem">產品管理</a>
        <div class="subItem">
            <div>
                <a href="/good/list">產品一覽</a>
                <a href="/good/category/list">分類一覽</a>
                <a href="/good/order/list">訂單一覽</a>
                <a href="/good/tag/list">屬性一覽</a>
            </div>
        </div>
    </div>
    <div class="menuItem">
        <a class="mainItem">帳號管理</a>
        <div class="subItem">
            <div>
                <a href="/admin/changeInfo">更改帳號資料</a>
                <a href="/admin/changePassword">更改密碼</a>
                <a href="/admin/create">建立新管理者</a>
            </div>
        </div>
    </div>
    <div class="menuItem">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a class="mainItem" href="route('logout')" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                登出
            </a>
        </form>
    </div>
</div>
