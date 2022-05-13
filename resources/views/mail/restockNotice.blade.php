<style>
    p {
        font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
    }
    h6{
        display:block;
        font-size:0.9rem;
    }
</style>
<div style="background-color:#edf2f7;width:100%;height:100%;display:flex;align-items:center;justify-content:center;padding-top:70px;padding-bottom: 50px;">
    <div style="margin:auto;">
        <h2 style="font-size:1.1rem;font-weight: bold;text-align: center">{{ config('app.name') }}</h2>
        <div style="background-color:white;padding:30px;color:#666">
            <div style="display:grid;grid-template-columns: 1fr 200px">
                <div style="grid-column-start: 1;padding-right:25px;">
                    <p style="font-weight: 600">親愛的{{ $user }}您好：</p>
                    <p>您關注的以下商品</p>
                    @foreach ($type as $t)
                        <h6>{{ $good }} - {{ $t }}</h6>
                    @endforeach
                    <p>已經進貨了，若有想要購買的話，歡迎點擊下方連結前往選購</p>
                </div>
                <div style="grid-column-start: 2;display:flex;align-items: center;">
                    <img src="{{ url($cover) }}" alt="" style="width:200px;height: auto">
                </div>
            </div>
            <div style="text-align: center;">
                <a href="{{ url('/o/good/') . '/' . $id }}" style="background-color: black;color:white;padding:7px 15px;border-radius: 10px;display: inline-block;margin-top:7px;cursor: pointer;text-decoration-line: none;">點我前往選購</a>
            </div>

            <p>
                {{ config('app.name') }}
            </p>
            <p>
                @lang('mail.Regards')
            </p>
        </div>
        <hr>
        <p>@lang('mail.Notice')</p>
    </div>
</div>
