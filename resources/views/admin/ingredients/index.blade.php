<x-app-layout>
    <div class="min-h-screen bg-amber-50">
        <!-- Шапка с кулинарным фоном -->
        <div class="bg-gradient-to-r from-amber-400 to-amber-600 py-8 shadow-md">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold text-center text-white font-serif">Моя кулинарная книга</h1>
                <p class="text-center text-amber-100 mt-2">Управление вашими рецептами и ингредиентами</p>
            </div>
        </div>

        <!-- Уведомления в стиле кухонных этикеток -->
        <div id="successMessage" class="hidden fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-full shadow-lg flex items-center space-x-3 opacity-0 transition-opacity duration-500 z-50 border-2 border-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-medium">Рецепт успешно добавлен!</span>
        </div>

        <div id="errorMessage" class="hidden fixed top-5 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-full shadow-lg flex items-center space-x-3 opacity-0 transition-opacity duration-500 z-50 border-2 border-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="font-medium">Произошла ошибка. Пожалуйста, попробуйте снова.</span>
        </div>

        <div class="container mx-auto px-4 py-8">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm max-w-4xl mx-auto">
                    <div class="font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Ошибка в рецепте!
                    </div>
                    <ul class="list-disc list-inside mt-2 pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Фильтры и сортировка -->
            <div class="mb-8 bg-white p-4 rounded-xl shadow-sm border border-amber-100 max-w-4xl mx-auto">
                <form action="{{ route('profile.ingredients.index') }}" method="GET">
                    <div class="flex flex-wrap gap-4 justify-between items-center">
                        <div class="flex-1 min-w-[200px]">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Поиск рецептов</label>
                            <input type="text" name="search" id="search" placeholder="Название, ингредиент..." value="{{ request('search') }}" class="w-full border border-amber-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="sort" class="block text-sm font-medium text-gray-700">Сортировать по:</label>
                            <select name="sort" id="sort" class="border border-amber-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                                <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Дате создания (убыв.)</option>
                                <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Дате создания (возр.)</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Названию (А-Я)</option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Названию (Я-А)</option>
                            </select>
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition-colors shadow-md hover:shadow-lg">
                                Применить
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if ($recipes->isEmpty())
                <div class="bg-white rounded-xl shadow-md p-8 text-center max-w-2xl mx-auto border-2 border-amber-200">
                    <div class="text-5xl mb-4">🍳</div>
                    <p class="text-xl text-gray-700 mb-4">Ваша кулинарная книга пока пуста</p>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-medium py-3 px-6 rounded-full transition-colors shadow-md hover:shadow-lg">
                        Создать первый рецепт
                    </a>
                </div>
            @else
                <!-- Сетка рецептов -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($recipes as $recipe)
                        <!-- Карточка рецепта -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-amber-100">
                            <!-- Изображение с эффектом меню -->
                            <div class="relative h-48 w-full">
                                @if ($recipe->image_url)
                                    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-amber-200 to-amber-400 flex items-center justify-center">
                                        <span class="text-white text-lg font-medium">🍽️ Нет фото</span>
                                    </div>
                                @endif
                                <!-- Статус как стикер -->
                                <div class="absolute top-3 left-3 bg-white px-3 py-1 rounded-full shadow-sm flex items-center">
                                    <span class="w-2 h-2 rounded-full mr-2 {{ 
                                        $recipe->status === 'pending' ? 'bg-yellow-500' : 
                                        ($recipe->status === 'approved' ? 'bg-green-500' : 'bg-red-500') 
                                    }}"></span>
                                    <span class="text-xs font-medium {{ 
                                        $recipe->status === 'pending' ? 'text-yellow-700' : 
                                        ($recipe->status === 'approved' ? 'text-green-700' : 'text-red-700') 
                                    }}">
                                        {{ $recipe->status === 'pending' ? 'На проверке' : 
                                           ($recipe->status === 'approved' ? 'Одобрен' : 'Отклонен') }}
                                    </span>
                                </div>
                                <!-- Время приготовления -->
                                <div class="absolute bottom-3 right-3 bg-black bg-opacity-70 text-white px-3 py-1 rounded-full text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $recipe->cooking_time }} мин
                                </div>
                            </div>

                            <!-- Содержимое карточки -->
                            <div class="p-5">
                                <!-- Заголовок и метаданные -->
                                <div class="mb-4">
                                    <h2 class="text-xl font-bold text-gray-800 mb-2 font-serif">{{ $recipe->title }}</h2>
                                    
                                    <!-- Метаданные в виде значков -->
                                    <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                                        <span class="flex items-center bg-amber-50 px-2 py-1 rounded-full">
                                            <svg class="w-4 h-4 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            {{ $recipe->calories }} ккал
                                        </span>
                                        <span class="flex items-center bg-amber-50 px-2 py-1 rounded-full">
                                            <svg class="w-4 h-4 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            @php
                                                $servings = $recipe->servings;
                                                $lastDigit = $servings % 10;
                                                $lastTwoDigits = $servings % 100;
                                                $word = 'порций';

                                                if ($lastDigit == 1 && $lastTwoDigits != 11) {
                                                    $word = 'порция';
                                                } elseif ($lastDigit >= 2 && $lastDigit <= 4 && ($lastTwoDigits < 10 || $lastTwoDigits >= 20)) {
                                                    $word = 'порции';
                                                }
                                            @endphp
                                            {{ $recipe->servings }} {{ $word }}
                                        </span>
                                        <span class="flex items-center bg-amber-50 px-2 py-1 rounded-full">
                                            <svg class="w-4 h-4 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                            </svg>
                                            {{ $recipe->difficulty === 'easy' ? 'Легко' : ($recipe->difficulty === 'medium' ? 'Средне' : 'Сложно') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Блок с БЖУ в виде диаграммы -->
                                <div class="mb-4 bg-amber-50 p-3 rounded-lg">
                                    <h3 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                        </svg>
                                        Пищевая ценность
                                    </h3>
                                    <div class="flex items-center">
                                        <div class="w-16 h-16 mr-3 relative">
                                            <!-- Круговая диаграмма для калорий -->
                                            <svg class="w-full h-full" viewBox="0 0 36 36">
                                                <path d="M18 2.0845
                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                                    fill="none"
                                                    stroke="#E5E7EB"
                                                    stroke-width="3" />
                                                <path d="M18 2.0845
                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                                    fill="none"
                                                    stroke="#F59E0B"
                                                    stroke-width="3"
                                                    stroke-dasharray="{{ min(($recipe->calories / 500) * 100, 100) }}, 100"
                                                    stroke-linecap="round" />
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-amber-600">
                                                {{ $recipe->calories }} ккал
                                            </div>
                                        </div>
                                        <div class="flex-1 grid grid-cols-3 gap-2">
                                            <div class="text-center">
                                                <div class="text-xs text-gray-500">Белки</div>
                                                <div class="text-sm font-semibold text-blue-600">{{ $recipe->proteins ?? 0 }}г</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xs text-gray-500">Жиры</div>
                                                <div class="text-sm font-semibold text-yellow-600">{{ $recipe->fats ?? 0 }}г</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xs text-gray-500">Углеводы</div>
                                                <div class="text-sm font-semibold text-green-600">{{ $recipe->carbs ?? 0 }}г</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Описание с кнопкой "Читать далее" -->
                                <div class="mb-4">
                                    <p class="text-gray-600 text-sm mb-2 line-clamp-3" id="description-{{ $recipe->id }}">
                                        {{ $recipe->description }}
                                    </p>
                                    @if (strlen($recipe->description) > 150)
                                        <button onclick="toggleDescription({{ $recipe->id }})" 
                                                class="text-amber-600 hover:text-amber-800 text-sm font-medium flex items-center">
                                            Читать далее
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <!-- Ингредиенты с иконкой списка покупок -->
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Ингредиенты
                                    </h3>
                                    <ul class="space-y-2">
                                        @foreach ($recipe->ingredients as $ingredient)
                                            <li class="flex justify-between items-center bg-amber-50 p-2 rounded-md text-sm">
                                                <span class="flex items-center">
                                                    <span class="w-4 h-4 mr-2 flex items-center justify-center text-amber-600">
                                                        •
                                                    </span>
                                                    {{ $ingredient->name }} — {{ $ingredient->quantity }} {{ $ingredient->unit }}
                                                </span>
                                                @if (auth()->user()->id === $recipe->user_id || auth()->user()->is_admin)
                                                    <div class="flex space-x-2">
                                                        <button onclick="openEditIngredientModal('{{ json_encode($ingredient) }}')" 
                                                                class="text-amber-600 hover:text-amber-800 text-xs flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            Ред.
                                                        </button>
                                                        <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="text-red-500 hover:text-red-700 text-xs flex items-center"
                                                                    onclick="return confirm('Удалить этот ингредиент?')">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                Удал.
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Форма добавления ингредиента -->
                                @if (auth()->user()->id === $recipe->user_id || auth()->user()->is_admin)
                                    <div class="mb-4 p-3 bg-amber-50 rounded-lg border border-amber-100">
                                        <h4 class="text-xs font-medium text-gray-700 mb-2 uppercase tracking-wider">Добавить ингредиент</h4>
                                        <form action="{{ route('ingredients.store') }}" method="POST" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                                            <div class="grid grid-cols-3 gap-2">
                                                <input type="text" name="name" placeholder="Название" 
                                                       class="border border-amber-200 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                                                <input type="number" name="quantity" placeholder="Кол-во" 
                                                       class="border border-amber-200 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                                                <select name="unit" class="border border-amber-200 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                                                    <option value="г">г</option>
                                                    <option value="кг">кг</option>
                                                    <option value="мл">мл</option>
                                                    <option value="л">л</option>
                                                    <option value="шт">шт</option>
                                                    <option value="ст. ложка">ст. ложка</option>
                                                    <option value="ч. ложка">ч. ложка</option>
                                                    <option value="чашка">чашка</option>
                                                </select>
                                            </div>
                                            <button type="submit" 
                                                    class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2 px-4 rounded-lg text-sm transition-colors shadow-sm hover:shadow-md flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Добавить
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                <!-- Шаги приготовления -->
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Шаги приготовления
                                    </h3>
                                    <ol class="list-decimal list-inside space-y-2 text-sm">
                                        @foreach ($recipe->steps as $index => $step)
                                            <li class="bg-amber-50 p-2 rounded-md">
                                                <div class="flex justify-between">
                                                    <span class="flex-grow">{{ $step->description }}</span>
                                                    @if (auth()->user()->id === $recipe->user_id || auth()->user()->is_admin)
                                                        <div class="flex space-x-2 ml-3">
                                                            <button onclick="openEditStepModal('{{ json_encode($step) }}')" 
                                                                    class="text-amber-600 hover:text-amber-800 text-xs flex items-center">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                                Ред.
                                                            </button>
                                                            <form action="{{ route('steps.destroy', $step->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="text-red-500 hover:text-red-700 text-xs flex items-center"
                                                                        onclick="return confirm('Удалить этот шаг?')">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                    Удал.
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>

                                <!-- Форма добавления шага -->
                                @if (auth()->user()->id === $recipe->user_id || auth()->user()->is_admin)
                                    <div class="mb-4 p-3 bg-amber-50 rounded-lg border border-amber-100">
                                        <h4 class="text-xs font-medium text-gray-700 mb-2 uppercase tracking-wider">Добавить шаг</h4>
                                        <form action="{{ route('steps.store', $recipe->id) }}" method="POST" class="space-y-2">
                                            @csrf
                                            <textarea name="description" rows="2" placeholder="Опишите этот шаг..." 
                                                      class="w-full border border-amber-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-300"></textarea>
                                            <button type="submit" 
                                                    class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2 px-4 rounded-lg text-sm transition-colors shadow-sm hover:shadow-md flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Добавить шаг
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                <!-- Управление рецептом -->
                                @if (auth()->user()->id === $recipe->user_id || auth()->user()->is_admin)
                                    <div class="border-t border-amber-100 pt-3 flex justify-between items-center">
                                        <button type="button" onclick="event.preventDefault(); openEditRecipeModal('{{ json_encode($recipe) }}')" 
                                                class="text-amber-600 hover:text-amber-800 font-medium text-sm flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Редактировать
                                        </button>
                                        <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-500 hover:text-red-700 font-medium text-sm flex items-center"
                                                    onclick="return confirm('Удалить этот рецепт?')">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Модальные окна -->
    @include('partials.modals.edit-ingredient')
    @include('partials.modals.edit-recipe')
    @include('partials.modals.edit-step')
    @include('partials.modals.reject-recipe')

    <script>
        // Функции управления UI
        function showNotification(type) {
            const messageDiv = document.getElementById(`${type}Message`);
            messageDiv.classList.remove('hidden');
            messageDiv.classList.add('opacity-100');
            
            setTimeout(() => {
                messageDiv.classList.remove('opacity-100');
                setTimeout(() => messageDiv.classList.add('hidden'), 500);
            }, 3000);
        }

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function toggleDescription(recipeId) {
            const desc = document.getElementById(`description-${recipeId}`);
            desc.classList.toggle('line-clamp-3');
            
            const btn = desc.nextElementSibling;
            if (btn && btn.tagName === 'BUTTON') {
                btn.textContent = desc.classList.contains('line-clamp-3') ? 'Читать далее' : 'Свернуть';
            }
        }

        function openEditIngredientModal(ingredient) {
            ingredient = typeof ingredient === 'string' ? JSON.parse(ingredient) : ingredient;
            
            const form = document.getElementById('editIngredientForm');
            form.action = `/ingredients/${ingredient.id}`;
            
            document.getElementById('edit_name').value = ingredient.name;
            document.getElementById('edit_quantity').value = ingredient.quantity;
            document.getElementById('edit_unit').value = ingredient.unit;
            
            toggleModal('editIngredientModal');
        }

        function openEditStepModal(step) {
            step = typeof step === 'string' ? JSON.parse(step) : step;
            
            const form = document.getElementById('editStepForm');
            form.action = `/steps/${step.id}`;
            document.getElementById('edit_step_description').value = step.description;
            
            toggleModal('editStepModal');
        }

        function openEditRecipeModal(recipe) {
            recipe = typeof recipe === 'string' ? JSON.parse(recipe) : recipe;
            
            document.getElementById('edit_recipe_id').value = recipe.id;
            document.getElementById('edit_title').value = recipe.title;
            document.getElementById('edit_description').value = recipe.description;
            document.getElementById('edit_proteins').value = recipe.proteins || '';
            document.getElementById('edit_fats').value = recipe.fats || '';
            document.getElementById('edit_carbs').value = recipe.carbs || '';
            document.getElementById('edit_cooking_time').value = recipe.cooking_time;
            document.getElementById('edit_calories').value = recipe.calories;
            document.getElementById('edit_servings').value = recipe.servings;
            document.getElementById('edit_difficulty').value = recipe.difficulty;
            document.getElementById('edit_category_id').value = recipe.category_id;
            document.getElementById('editRecipeForm').action = `/recipes/${recipe.id}`;
            
            toggleModal('editRecipeModal');
        }

        function openRejectModal(recipeId) {
            document.getElementById('reason').value = '';
            document.getElementById('rejectForm').action = `/admin/recipes/${recipeId}/reject`;
            toggleModal('rejectModal');
        }

        function closeRejectModal() {
            toggleModal('rejectModal');
        }

        // Инициализация уведомлений
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success')) showNotification('success'); @endif
            @if(session('error')) showNotification('error'); @endif
        });
    </script>
</x-app-layout>