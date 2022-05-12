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
        <div style="background-color:white;padding:30px;color:#666;width:570px">

                    <p style="font-weight: 600">親愛的{{ $user }}您好：</p>
                    <p>感謝您的購買！以下是您的訂單資訊：</p>
                    <p>訂單編號：{{ $serial }}</p>
                    @foreach ($details as $d)
                        <h6>{{ $d->getName() }} - {{ $d->getType() }}  $ {{ $d->amount }}  x{{ $d->quantity }}</h6>
                    @endforeach
                    <p>付款方式：{{ $payment->name }}</p>
                    <p>運費：{{ $freight }}</p>
                    <p>總金額：{{ $total }}</p>
            <div style="text-align: center;">
                <a href="{{ url('/order') . '/' . $serial }}" style="background-color: black;color:white;padding:7px 15px;border-radius: 10px;display: inline-block;margin-top:7px;cursor: pointer;text-decoration-line: none;">點此檢視完整訂單資訊</a>
            </div>

            <p>
                {{ config('app.name') }}
            </p>
            <p>
                @lang('mail.Regards')
            </p>
        </div>
    </div>
</div>
