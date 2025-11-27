<x-app-layout>
    <div class="bg-yellow-50 min-h-screen p-8 flex flex-wrap">
        <!-- Блок фильтров слева -->
        <div class="w-full md:w-1/4 p-4">
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h5 class="font-bold text-lg mb-4 text-red-600">Фильтры</h5>
                <form method="GET" action="{{ route('home.index') }}">
                    <div class="mb-4">
                        <label for="filterCategory" class="block text-sm font-medium text-gray-600">Категория</label>
                        <select name="filterCategory" class="border rounded-lg w-full p-2 focus:ring-red-300" id="filterCategory">
                            <option value="">Все</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="filterTime" class="block text-sm font-medium text-gray-600">Время приготовления</label>
                        <select name="filterTime" class="border rounded-lg w-full p-2 focus:ring-red-300" id="filterTime">
                            <option value="">Все</option>
                            <option value="До 30 минут">До 30 минут</option>
                            <option value="30-60 минут">30-60 минут</option>
                            <option value="Более 60 минут">Более 60 минут</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="filterRating" class="block text-sm font-medium text-gray-600">Рейтинг</label>
                        <select name="filterRating" class="border rounded-lg w-full p-2 focus:ring-red-300" id="filterRating">
                            <option value="">Все</option>
                            <option value="1">1 звезда и выше</option>
                            <option value="2">2 звезды и выше</option>
                            <option value="3">3 звезды и выше</option>
                            <option value="4">4 звезды и выше</option>
                            <option value="5">5 звезд</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition duration-200">
                        Применить фильтр
                    </button>
                </form>
            </div>
            <div class="mt-6 bg-white p-4 rounded-lg shadow-lg">
                <h5 class="font-bold text-lg mb-4 text-red-600">Хотите добавить свой рецепт?</h5>
                <a
                    class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition duration-200" href="{{route('recipes.index')}}">
                    Перейти в личный кабинет
                </a>
            </div>
            @auth
                @if(auth()->user()->role === 'admin')
                @endif
            @endauth
        </div>

        <!-- Блок поиска и баннера справа -->
        <div class="w-full md:w-3/4 p-4">
            <div class="flex items-center mb-4">
                <form method="GET" action="{{ route('home.index') }}" class="flex w-full">
                    <input type="text" name="search" class="border rounded-l-lg w-full p-2 focus:ring-red-300"
                        placeholder="Поиск по рецептам" value="{{ request('search') }}">
                    <button type="submit"
                        class="bg-red-500 text-white py-2 px-4 rounded-r-lg hover:bg-red-600 transition duration-200">Поиск</button>
                </form>
            </div>

            <h2 class="text-center text-xl font-bold mt-5 text-red-600">Категории</h2>

            <div x-data="{ activeTab: null }" class="mt-4">
                <!-- Кнопки вкладок -->
                <div class="flex justify-center space-x-4">
                    @foreach ($categories as $category)
                        <button
                            @click="activeTab = activeTab === '{{ $category->id }}' ? null : '{{ $category->id }}'"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg transition duration-300 hover:bg-red-600">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>

                <!-- Контент вкладок -->
                <div class="mt-4">
                    @foreach ($categories as $category)
                        <div x-show="activeTab === '{{ $category->id }}'" class="bg-white p-4 rounded-lg shadow-lg">
                            <h3 class="font-bold text-gray-800">{{ $category->name }}</h3>
                            <p class="text-gray-600">{{ $category->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Блок популярных рецептов -->
            <div class="py-12">
                <h3 class="text-xl font-bold mb-4 text-red-600">Популярные рецепты</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @forelse($popularRecipes as $recipe)
                        <div
                            class="bg-white shadow-lg rounded-lg overflow-hidden transform hover:scale-105 transition duration-300">
                            <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h4 class="font-bold text-lg text-gray-800">{{ $recipe->title }}</h4>
                                <p class="text-gray-600">{{ Str::limit($recipe->description, 100) }}</p>
                                <p class="text-sm text-gray-500 mt-2">Автор: {{ $recipe->user->username }}</p>
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
                                <a href="{{ route('recipes.show', $recipe->id) }}"
                                    class="text-red-500 hover:underline mt-2 inline-block">Подробнее</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">Популярные рецепты не найдены.</p>
                    @endforelse
                </div>
            </div>
            <!-- Блок рецептов -->
            <div class="py-6">
                <h3 class="text-xl font-bold mb-4 text-red-600">Недавно добавленные рецепты</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @forelse($recipes->where('status', 'approved') as $recipe)
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden transform hover:scale-105 transition duration-300">
                            <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h4 class="font-bold text-lg text-gray-800">{{ $recipe->title }}</h4>
                                <p class="text-gray-600">{{ Str::limit($recipe->description, 100) }}</p>
                                <p class="text-sm text-gray-500 mt-2">Автор: {{ $recipe->user->username }}</p>
                                <a href="{{ route('recipes.show', $recipe->id) }}"
                                    class="text-red-500 hover:underline mt-2 inline-block">Подробнее</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">Нет доступных рецептов.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>
