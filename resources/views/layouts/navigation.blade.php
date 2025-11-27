<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Левая часть навигации -->
            <div class="flex">
                <!-- Логотип -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home.index') }}" class="flex items-center space-x-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        <span class="font-bold text-xl text-gray-900">CookBook</span>
                    </a>
                </div>

                <!-- Основное меню -->
                <div class="hidden space-x-1 sm:ml-6 sm:flex">
                    <a href="{{ route('home.index') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home.index') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Главная
                    </a>

                    <a href="{{ route('catalog') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('catalog') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Каталог рецептов
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Личный кабинет
                        </a>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.index') }}" 
                               class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.*') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Админ панель
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Правая часть навигации -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Профиль пользователя -->
                    <div class="ml-3 relative" id="userMenuContainer">
                        <button id="userMenuButton"
                                class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 focus:outline-none">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" 
                                     alt="Avatar" 
                                     class="h-8 w-8 rounded-full object-cover">
                            @else
                                <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <span class="text-red-600 font-medium text-sm">
                                        {{ substr(auth()->user()->username ?? auth()->user()->email, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <span class="font-medium text-sm">{{ auth()->user()->username }}</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Выпадающее меню -->
                        <div id="userMenu" 
                             class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Профиль
                            </a>
                            <a href="{{ route('profile.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                История просмотров
                            </a>
                            <x-nav-link :href="route('profile.notifications')" :active="request()->routeIs('profile.notifications')">
                                <div class="relative">
                                    {{ __('Уведомления') }}
                                </div>
                            </x-nav-link>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Выйти
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="space-x-3">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            Войти
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Регистрация
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Мобильное меню -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Мобильное меню (развернутое) -->
    <div :class="{'block': open, 'hidden': !open}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('home.index') ? 'border-red-500 text-red-700 bg-red-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                Главная
            </a>
            <a href="{{ route('catalog') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('catalog') ? 'border-red-500 text-red-700 bg-red-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                Каталог рецептов
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-red-500 text-red-700 bg-red-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                    Личный кабинет
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.*') ? 'border-red-500 text-red-700 bg-red-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                        Админ панель
                    </a>
                @endif
            @endauth
        </div>

        <!-- Мобильное меню профиля -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover">
                    @else
                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                            <span class="text-red-600 font-medium">
                                {{ substr(auth()->user()->username ?? auth()->user()->email, 0, 1) }}
                            </span>
                        </div>
                    @endif
                    <div class="ml-3">
                        <div class="font-medium text-base text-gray-800">{{ auth()->user()->username }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Профиль
                    </a>
                    <a href="{{ route('profile.history') }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        История просмотров
                    </a>
                    <x-nav-link :href="route('profile.notifications')" :active="request()->routeIs('profile.notifications')">
                        <div class="relative">
                            {{ __('Уведомления') }}
                        </div>
                    </x-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-red-600 hover:text-red-700 hover:bg-red-50">
                            Выйти
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="space-y-1">
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Войти
                    </a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Регистрация
                    </a>
                </div>
            </div>
        @endauth
    </div>
</nav>

<script>
    // Добавляем скрипт после определения HTML
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('userMenuButton');
        const menu = document.getElementById('userMenu');
        const container = document.getElementById('userMenuContainer');

        // Открытие/закрытие меню по клику на кнопку
        menuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('hidden');
        });

        // Закрытие меню при клике вне его
        document.addEventListener('click', function(e) {
            if (!container.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        // Закрытие меню по нажатию Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                menu.classList.add('hidden');
            }
        });
    });
</script>
