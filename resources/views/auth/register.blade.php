<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                {{-- <x-application-logo class="w-20 h-20 fill-current text-gray-500" /> --}}
                <img src="{{ asset('/assets/source/eastfarmLogo.png') }}" style="height: 250px;width:auto">
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('姓名')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('電子信箱')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-label for="tel" :value="__('電話')" />

                <x-input id="tel" class="block mt-1 w-full" type="tel" name="tel" :value="old('tel')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('密碼(8碼以上)')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <span class="showPassword"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('確認密碼')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
                <span class="showPassword"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('已經有帳號了?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('註冊') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
<style>
    .showPassword {
        float: right;
        transform: translate(-17px, -33px) scale(1.4);
        cursor: pointer;
    }
    </style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" /></link>
<script type="text/javascript" src="{{ asset('js/showPassword.js') }}"></script>
