<x-app-layout>
    <div class="bg-gradient-to-r from-yellow-50 via-yellow-100 to-yellow-200 min-h-screen p-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-2xl">
            <!-- Введение -->
            <h1 class="text-5xl font-extrabold text-center text-red-600 mb-8">О нас</h1>
            <p class="text-gray-800 leading-relaxed text-xl mb-8">
                Добро пожаловать в наш кулинарный справочник! Мы создаем уникальное пространство для тех, кто любит готовить и делиться рецептами. Здесь каждый найдет вдохновение для своих кулинарных экспериментов!
            </p>

            <!-- Блок о миссии и изображение -->
            <div class="flex flex-col md:flex-row gap-8 items-center mb-12">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836" alt="Кулинарные ингредиенты"
                     class="rounded-2xl shadow-lg w-full md:w-1/2 object-cover transition-transform transform hover:scale-105">
                <div class="text-gray-800 flex-1">
                    <h2 class="text-3xl font-semibold text-red-600 mb-6">Наша миссия</h2>
                    <p class="leading-relaxed text-lg">
                        Мы стремимся делиться вкусными и полезными рецептами, чтобы вдохновлять людей на кулинарные открытия. Наша цель — объединять людей за общим столом и пробуждать их любовь к еде.
                    </p>
                </div>
            </div>

            <!-- Преимущества платформы -->
            <div class="bg-yellow-100 p-8 rounded-2xl shadow-md mb-12">
                <h3 class="text-3xl font-semibold text-red-600 mb-6">Почему выбирают нас</h3>
                <ul class="list-disc list-inside text-gray-800 space-y-4 text-lg">
                    <li>Обширная база рецептов на любой вкус и уровень сложности.</li>
                    <li>Фильтры по категориям и времени приготовления для быстрого поиска.</li>
                    <li>Возможность сохранять любимые рецепты и делиться своими.</li>
                </ul>
            </div>

            <!-- Присоединение к сообществу -->
            <div class="text-center mt-12">
                <h4 class="text-2xl font-semibold text-red-600 mb-6">Присоединяйтесь к нам!</h4>
                <p class="text-gray-800 mb-8 text-lg">Хотите стать частью нашего кулинарного сообщества? Присоединяйтесь, делитесь рецептами и находите вдохновение каждый день!</p>
                <a href="{{ route('register') }}" class="bg-red-500 text-white py-3 px-8 rounded-full shadow-xl hover:bg-red-600 transition duration-300 ease-in-out transform hover:scale-105">
                    Зарегистрироваться
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
