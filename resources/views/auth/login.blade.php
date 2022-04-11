<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                {{-- <x-application-logo class="w-20 h-20 fill-current text-gray-500" /> --}}
                <img src="/storage/source/eastfarmLogo.png" style="height: 300px;width:auto">
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('電子郵件')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('密碼')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <span class="showPassword"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('記住我') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('忘記密碼了?') }}
                    </a>
                @endif

                <x-button class="ml-3">
                    {{ __('登入') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
<style>
.showPassword {
    float: right;
    transform: translate(-17px, -33px) scale(1.8);
    cursor: pointer;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" /></link>
<script>
    $(()=>{
        $('.showPassword').click(function(){
            let t = $(this).prev('input');
            if(t.hasClass('show')){
                t.attr('type','password');
                t.removeClass('show');
                $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
            }else{
                t.attr('type','text');
                t.addClass('show');
                $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
            }
        })
    })
</script>
