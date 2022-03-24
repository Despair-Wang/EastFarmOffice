<div class="row">
    <div class="col-12 col-lg-4">
        <p>Tel:(03)511-0098</p>
    </div>
    <div class="col-12 col-lg-4">
        <Address>Address:新竹縣竹東鎮和江街370-3號</Address>
    </div>
    <div class="col-12 col-lg-4">

    </div>
</div>
<h6 class="ali-r">Coryright© 2022</h6>
@if (Auth::check() && Cache::has(Auth::id()))
    <div id="cart">
        <div>
            <div id="cartMessage" class="curP">
                <p>前往結帳</p>
            </div>
            <i class="fa fa-shopping-cart curP" aria-hidden="true"></i>
            <div id="cartCount" class="curP">
                {{ count(Cache::get(Auth::id())) }}
            </div>
        </div>
    </div>
@endif
