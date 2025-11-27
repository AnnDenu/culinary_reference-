<x-guest-layout>
    <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-8">
        <style>
            .back-link {
                display: flex;
                align-items: center;
                text-decoration: underline;
                color: #1a202c;
                /* deep-blue */
            }

            .back-link:hover {
                color: #2d3748;
                /* darker-green */
            }

            .icon-wrapper {
                background-color: #edf2f7;
                /* gray-200 */
                border-radius: 4px;
                /* rounded-md */
                padding: 4px;
                /* p-1 */
                margin-right: 8px;
                /* mr-2 */
            }
        </style>

        <a class="back-link" href="{{ route('home.index') }}">
            <span class="icon-wrapper">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </span>
        </a>
        <h2 class="text-2xl font-semibold text-center text-darker-green mb-6">Регистрация в кулинарный справочник</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- username -->
            <div>
                <x-input-label for="username" :value="__('ФИО')" class="text-darker-green" />
                <x-text-input id="username"
                    class="block mt-1 w-full border-darker-green focus:border-deep-blue focus:ring-deep-blue rounded-md"
                    type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2 text-darker-red" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" class="text-darker-green" />
                <x-text-input id="email"
                    class="block mt-1 w-full border-darker-green focus:border-deep-blue focus:ring-deep-blue rounded-md"
                    type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-darker-red" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Пароль')" class="text-darker-green" />
                <x-text-input id="password"
                    class="block mt-1 w-full border-darker-green focus:border-deep-blue focus:ring-deep-blue rounded-md"
                    type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-darker-red" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Повторить пароль')" class="text-darker-green" />
                <x-text-input id="password_confirmation"
                    class="block mt-1 w-full border-darker-green focus:border-deep-blue focus:ring-deep-blue rounded-md"
                    type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-darker-red" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="text-sm text-deep-blue hover:text-darker-green underline" href="{{ route('login') }}">
                    {{ __('Уже зарегистрированы?') }}
                </a>

                <x-primary-button
                    class="ml-4 bg-darker-green hover:bg-deep-green focus:ring-deep-blue rounded-full px-6 py-2">
                    {{ __('Зарегистрироваться') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>