<div class="row">
    <div class="col-12 col-lg-4">
        <p>Tel:(03)511-0098</p>
    </div>
    <div class="col-12 col-lg-4">
        <Address>Address:新竹縣竹東鎮和江街370-3號2F</Address>
    </div>
    <div class="col-12 col-lg-4">

    </div>
</div>
<h6 class="ali-r">Coryright© 2022</h6>
@if (Auth::check() && Cache::has('good' . Auth::id()))
    <div id="cart" onclick="location.href='/orderCheck'">
        <div>
            <div id="cartMessage" class="curP">
                <p>前往結帳</p>
            </div>
            <i class="fa fa-shopping-cart curP" aria-hidden="true"></i>
            <div id="cartCount" class="curP">
                {{ count(Cache::get('good' . Auth::id())) }}
            </div>
        </div>
    </div>
@endif
