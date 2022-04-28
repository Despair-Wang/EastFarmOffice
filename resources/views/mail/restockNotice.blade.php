@lang('mail.Hello')
<p>親愛的顧客您好：</p>
<p>先前缺貨的商品{{ $good }}已經進貨了，若有想要購買的話，歡迎點擊下方連結前往選購</p>
<img src="{{ url($cover) }}" alt="" style="width:250px;height: auto">
<a>點我前往選購</a>
{{ config('app.name') }}<br>
@lang('mail.Regards')
