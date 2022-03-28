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
        <a id="goodMenu" class="dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
            <span class="sr-only">Toggle DropDown</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="goodMenu">
        @foreach($categories as $key => $good)
            @php
            $key = explode(',',$key);
            $id = $key[0];
            $name = $key[1];
            @endphp
            <div>
                <a href="{{ url('/o/good-list') . '/' . $id }}">{{ $name }}</a>
                @if(count($good) > 0)
                    <a class="subDrop dropdown-toggle dropdown-toggle-split">
                        <span class="sr-only">Toggle DropDown</span>
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
<script>
    $(()=>{
        $('.subDrop').mouseenter(function(){
            $(this).next().show();
        })

        $('.subMenu').mouseleave(function(){
            console.log('leave');
            $(this).hide();
        })
    })
</script>
