<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                {{-- <x-application-logo class="w-20 h-20 fill-current text-gray-500" /> --}}
                <img src="{{ asset('/assets/source/eastfarmLogo.png') }}" style="height: 250px;width:auto">
            </a>
        </x-slot>

        <div class="mb-4 text-lg text-gray-600">
            <p>請輸入您註冊時所用的信箱。</p>
            <p>我們將會發送一封郵件至您的信箱，您可以透過該信件來重新設定密碼。</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('電子信箱')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('發送郵件') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
