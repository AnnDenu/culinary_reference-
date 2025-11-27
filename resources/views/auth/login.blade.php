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
        <h2 class="text-2xl font-semibold text-center text-darker-green mb-6">Вход в кулинарный справочник</h2>

        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-darker-green" />
                <x-text-input id="email"
                              class="block mt-1 w-full border-darker-green focus:border-deep-blue focus:ring-deep-blue rounded-md"
                              type="email" name="email" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-darker-red" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Пароль')" class="text-darker-green" />
                <x-text-input id="password"
                              class="block mt-1 w-full border-darker-green focus:border-deep-blue focus:ring-deep-blue rounded-md"
                              type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-darker-red" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="text-sm text-deep-blue hover:text-darker-green underline" href="{{ route('register') }}">
                    {{ __('Нет аккаунта? Зарегистрироваться') }}
                </a>

                <x-primary-button
                    class="ml-4 bg-darker-green hover:bg-deep-green focus:ring-deep-blue rounded-full px-6 py-2">
                    {{ __('Войти') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
