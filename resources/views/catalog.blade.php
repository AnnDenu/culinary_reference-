<x-app-layout>
    <div class="bg-yellow-50 min-h-screen py-12 px-6 lg:px-16">
        <!-- Заголовок страницы -->
        <h1 class="text-4xl font-extrabold text-red-600 mb-10 text-center uppercase tracking-wide">
            Каталог рецептов
        </h1>

        <!-- Форма фильтрации и сортировки -->
        <form action="{{ route('catalog') }}" method="GET" class="mb-8">
            <!-- Поле поиска -->
            <div class="mb-6">
                <div class="max-w-xl mx-auto">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Поиск рецептов</label>
                    <div class="flex gap-2">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                            placeholder="Введите название рецепта или ингредиент..." 
                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 transition">
                            Найти
                        </button>
                    </div>
                </div>
            </div>

            <!-- Фильтрация по категориям -->
            <div class="flex flex-wrap gap-3 justify-center mb-6">
                @foreach($categories as $category)
                    <button
                        type="button"
                        class="bg-red-500 text-white py-2 px-5 rounded-full text-lg font-semibold transition transform hover:bg-red-600 hover:scale-105 {{ request('category') == $category->id ? 'ring-2 ring-red-700' : '' }}"
                        onclick="window.location.href='{{ route('catalog', array_merge(request()->query(), ['category' => $category->id])) }}'">
                        {{ $category->name }}
                    </button>
                @endforeach
                <a href="{{ route('catalog') }}"
                   class="bg-gray-500 text-white py-2 px-5 rounded-full text-lg font-semibold transition transform hover:bg-gray-600 hover:scale-105">
                    Показать все
                </a>
            </div>

            <!-- Фильтры по питательным веществам -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="calories" class="block text-sm font-medium text-gray-700">Максимальные калории</label>
                    <input type="number" name="calories" id="calories" value="{{ request('calories') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Белки (г)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <input type="number" step="0.01" name="min_proteins" placeholder="Мин" value="{{ request('min_proteins') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <input type="number" step="0.01" name="max_proteins" placeholder="Макс" value="{{ request('max_proteins') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Жиры (г)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <input type="number" step="0.01" name="min_fats" placeholder="Мин" value="{{ request('min_fats') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <input type="number" step="0.01" name="max_fats" placeholder="Макс" value="{{ request('max_fats') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Углеводы (г)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <input type="number" step="0.01" name="min_carbs" placeholder="Мин" value="{{ request('min_carbs') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <input type="number" step="0.01" name="max_carbs" placeholder="Макс" value="{{ request('max_carbs') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <button type="submit" class="bg-red-500 text-white py-2 px-6 rounded-lg font-semibold transition hover:bg-red-600">
                    Применить фильтры
                </button>
            </div>
        </form>

        <!-- Сетка рецептов -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($recipes as $recipe)
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:shadow-xl hover:scale-105">
                    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}"
                         class="w-full h-44 object-cover">
                    <div class="p-5">
                        <h2 class="text-xl font-bold text-red-600 mb-2">{{ $recipe->title }}</h2>
                        <p class="text-gray-600 mt-2">{{ Str::limit($recipe->description, 100) }}</p>
                        <p class="text-sm text-gray-500 mt-2">Автор: {{ $recipe->user->username }}</p>

                        <!-- Время и рейтинг -->
                        <div class="flex justify-between items-center text-gray-600 text-sm">
                            <span>⏳ {{ $recipe->cooking_time }} мин</span>
                            <span class="flex items-center">
                                @php
                                    $rating = round($recipe->comments_avg_rating ?? 0);
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $rating)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 15.27L16.18 18l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 3.73L3.82 18z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 15.27L16.18 18l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 3.73L3.82 18z"/>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="ml-2 text-gray-500 text-sm">({{ number_format($recipe->comments_avg_rating, 1) }} / 5)</span>
                            </span>
                        </div>

                        <!-- Кнопка "Подробнее" -->
                        <a href="{{ route('recipes.show', $recipe->id) }}"
                           class="block mt-4 text-center bg-red-500 text-white py-2 rounded-lg font-semibold transition hover:bg-red-600">
                            Подробнее
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        function updateSort(value) {
            // Получаем текущие параметры URL
            const urlParams = new URLSearchParams(window.location.search);
            
            // Обновляем параметр сортировки
            urlParams.set('sort', value);
            
            // Перенаправляем на ту же страницу с новыми параметрами
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        }
    </script>
</x-app-layout>
