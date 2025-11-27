<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Мои рецепты и избранное') }}
        </h2>
    </x-slot>

    <!-- Уведомления -->
    @if (session('success'))
        <div id="alert-success" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md transition duration-200 z-50">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div id="alert-error" class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-md transition duration-200 z-50">
            {{ session('error') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div class="p-6 text-gray-900">
                    <!-- Основная сетка с двумя колонками -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        <!-- Левая колонка: Избранные рецепты -->
                        <div class="space-y-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-2xl font-semibold text-gray-800">Избранные рецепты</h3>
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">
                                    {{ $favorites->count() }} {{ trans_choice('рецепт|рецепта|рецептов', $favorites->count()) }}
                                </span>
                            </div>

                            @if($favorites->isEmpty())
                                <div class="bg-gray-50 rounded-lg p-8 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <p class="mt-4 text-gray-500">У вас пока нет избранных рецептов</p>
                                    <a href="{{ route('catalog') }}" class="mt-4 inline-block text-blue-500 hover:text-blue-600">
                                        Найти рецепты &rarr;
                                    </a>
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($favorites as $favorite)
                                        <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                            <div class="relative">
                                            <img src="{{ $favorite->recipe->image_url }}" alt="{{ $favorite->recipe->title }}"
                                                    class="w-full h-40 object-cover">
                                                <button class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-gray-100"
                                                    onclick="event.preventDefault(); document.getElementById('remove-favorite-{{ $favorite->id }}').submit()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <form id="remove-favorite-{{ $favorite->id }}" 
                                                    action="{{ route('recipes.removeFromFavorites', $favorite->recipe->id) }}" 
                                                    method="POST" class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                            <div class="p-4">
                                                <h4 class="font-semibold text-lg mb-1">{{ $favorite->recipe->title }}</h4>
                                                <p class="text-gray-600 text-sm mb-2">{{ Str::limit($favorite->recipe->description, 60) }}</p>
                                                <div class="flex justify-between items-center mt-4">
                                                    <span class="text-xs text-gray-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $favorite->recipe->cooking_time }} мин
                                                    </span>
                                                    <a href="{{ route('recipes.show', $favorite->recipe->id) }}"
                                                        class="text-sm text-blue-500 hover:text-blue-600 font-medium">
                                                        Открыть рецепт
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Правая колонка: Мои рецепты и комментарии -->
                        <div class="space-y-8">
                            <!-- Блок с комментариями -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800">Мои комментарии</h3>
                                    <button onclick="toggleModal('commentsModal')"
                                        class="flex items-center text-sm text-blue-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        Просмотреть все
                                    </button>
                                </div>

                                @if($comments->take(2)->isEmpty())
                                    <p class="text-gray-500 text-sm">Вы еще не оставляли комментарии</p>
                                @else
                                    <ul class="space-y-3">
                                        @foreach($comments->take(2) as $comment)
                                            <li class="border-b pb-3 last:border-b-0 last:pb-0">
                                                <a href="{{ route('recipes.show', $comment->recipe->id) }}" class="block hover:bg-gray-100 p-2 rounded -m-2">
                                                    <p class="font-medium text-gray-800">{{ $comment->recipe->title }}</p>
                                                    <p class="text-gray-600 text-sm mt-1">{{ Str::limit($comment->comment, 80) }}</p>
                                                    <p class="text-gray-400 text-xs mt-2">{{ $comment->created_at->diffForHumans() }}</p>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <!-- Блок с моими рецептами -->
                        <div>
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-2xl font-semibold text-gray-800">Мои рецепты</h3>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('profile.ingredients.index') }}" 
                                            class="flex items-center text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                            </svg>
                                            Ингредиенты
                                        </a>
                                        <button onclick="toggleModal('addRecipeModal')"
                                            class="flex items-center text-sm bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Добавить
                                        </button>
                                    </div>
                                </div>

                                @if($recipes->isEmpty())
                                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="mt-4 text-gray-500">У вас пока нет собственных рецептов</p>
                                        <button onclick="toggleModal('addRecipeModal')"
                                            class="mt-4 inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                                            Создать первый рецепт
                                        </button>
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($recipes as $recipe)
                                            <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                                <a href="{{ route('recipes.show', $recipe->id) }}" class="block">
                                                    <div class="relative">
                                                        @if($recipe->image_url)
                                                            <img src="{{ asset('storage/' . $recipe->image_url) }}" alt="{{ $recipe->title }}" 
                                                                class="w-full h-40 object-cover">
                                                        @else
                                                            <div class="w-full h-40 bg-gray-100 flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <span class="absolute bottom-2 right-2 bg-white/80 text-gray-800 text-xs px-2 py-1 rounded">
                                                            {{ $recipe->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                    <div class="p-4">
                                                        <h4 class="font-semibold text-lg mb-1">{{ $recipe->title }}</h4>
                                                        <div class="flex justify-between items-center mt-3">
                                                            <span class="text-xs text-gray-500">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                {{ $recipe->cooking_time }} мин
                                                            </span>
                                                            <span class="text-xs px-2 py-1 rounded-full 
                                                                @if($recipe->difficulty == 'easy') bg-green-100 text-green-800 
                                                                @elseif($recipe->difficulty == 'medium') bg-yellow-100 text-yellow-800 
                                                                @else bg-red-100 text-red-800 @endif">
                                                                {{ $recipe->difficulty == 'easy' ? 'Легко' : ($recipe->difficulty == 'medium' ? 'Средне' : 'Сложно') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-4">
                                        @if ($recipes instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                                             {{ $recipes->links() }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно комментариев -->
    <div id="commentsModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Мои комментарии</h3>
                    <button onclick="toggleModal('commentsModal')" class="text-gray-400 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                            </button>
                </div>

                                    @if($comments->isEmpty())
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="mt-4 text-gray-500">Вы еще не оставляли комментарии</p>
                    </div>
                                    @else
                    <ul class="divide-y divide-gray-200">
                                            @foreach($comments as $comment)
                            <li class="py-4">
                                <div class="flex justify-between">
                                    <a href="{{ route('recipes.show', $comment->recipe->id) }}" class="font-medium text-blue-600 hover:text-blue-800">
                                                            {{ $comment->recipe->title }}
                                                        </a>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                                </div>
                                <p class="mt-1 text-gray-600">{{ $comment->comment }}</p>
                                <div class="mt-2 flex justify-end">
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700" 
                                            onclick="return confirm('Удалить этот комментарий?')">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
            </div>
        </div>
    </div>

    <!-- Модальное окно добавления рецепта -->
    <div id="addRecipeModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Добавить новый рецепт</h3>
                    <button onclick="toggleModal('addRecipeModal')" class="text-gray-400 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                                    </button>
                                </div>

                <form id="addRecipeForm" method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Основная информация -->
                        <div class="space-y-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Название рецепта *</label>
                                <input type="text" id="title" name="title" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Категория *</label>
                                <select id="category_id" name="category_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание *</label>
                                <textarea id="description" name="description" rows="3" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Изображение *</label>
                                <input type="file" id="image" name="image" accept="image/*" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Форматы: JPG, PNG, GIF. Макс. размер: 2MB</p>
                                                </div>
                                                </div>

                        <!-- Детали рецепта -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="cooking_time" class="block text-sm font-medium text-gray-700 mb-1">Время (мин) *</label>
                                    <input type="number" id="cooking_time" name="cooking_time" min="1" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="servings" class="block text-sm font-medium text-gray-700 mb-1">Порции *</label>
                                    <input type="number" id="servings" name="servings" min="1" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                                </div>
                            
                            <div>
                                <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Сложность *</label>
                                <select id="difficulty" name="difficulty" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                        <option value="easy">Легко</option>
                                                        <option value="medium">Средне</option>
                                                        <option value="hard">Сложно</option>
                                                    </select>
                                                </div>
                            
                            <div>
                                <label for="calories" class="block text-sm font-medium text-gray-700 mb-1">Калорийность (на порцию)</label>
                                <input type="number" id="calories" name="calories" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                </div>
                                                </div>
                                            </div>

                                            <!-- Пищевая ценность -->
                                            <div class="mt-8">
                                                <h4 class="text-lg font-medium text-gray-800 mb-4">Пищевая ценность (на порцию)</h4>
                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                                    <div>
                                                        <label for="proteins" class="block text-sm font-medium text-gray-700 mb-1">Белки (г)</label>
                                                        <input type="number" id="proteins" name="proteins" min="0" step="0.1"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    </div>
                                                    <div>
                                                        <label for="fats" class="block text-sm font-medium text-gray-700 mb-1">Жиры (г)</label>
                                                        <input type="number" id="fats" name="fats" min="0" step="0.1"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    </div>
                                                    <div>
                                                        <label for="carbs" class="block text-sm font-medium text-gray-700 mb-1">Углеводы (г)</label>
                                                        <input type="number" id="carbs" name="carbs" min="0" step="0.1"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Ингредиенты -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-medium text-gray-800">Ингредиенты</h4>
                            <button type="button" onclick="addIngredient()" 
                                class="flex items-center text-sm bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Добавить
                            </button>
                        </div>
                        
                        <div id="ingredientList" class="space-y-3">
                            <div class="ingredient-item grid grid-cols-12 gap-3">
                                <div class="col-span-5">
                                    <input type="text" name="ingredients[0][name]" placeholder="Название" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                </div>
                                <div class="col-span-3">
                                    <input type="number" name="ingredients[0][quantity]" placeholder="Количество" min="0" step="0.1" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                </div>
                                <div class="col-span-3">
                                    <select name="ingredients[0][unit]" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
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
                                                    <div class="col-span-1 flex items-center">
                                                        <button type="button" onclick="removeIngredient(this)"
                                                            class="text-red-500 hover:text-red-700 p-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                    </div>
                                                </div>
                    
                    <!-- Шаги приготовления -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-medium text-gray-800">Шаги приготовления</h4>
                                                <button type="button" onclick="addStep()"
                                class="flex items-center text-sm bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Добавить шаг
                            </button>
                                            </div>

                        <div id="stepsList" class="space-y-4">
                            <div class="step-item flex gap-3">
                                <span class="text-lg font-bold mt-2">1.</span>
                                <textarea name="steps[0][description]" rows="2" placeholder="Подробно опишите этот шаг" required
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm h-10"></textarea>
                                <button type="button" onclick="removeStep(this)"
                                    class="ml-2 px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Удалить шаг</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end space-x-3">
                        <button type="button" onclick="toggleModal('addRecipeModal')"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Отмена
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Сохранить рецепт
                        </button>
                </div>
                </form>
            </div>
        </div>
    </div>

<script>
    let ingredientCount = 1; // Начальное количество ингредиентов
    let stepCount = 1; // Начальное количество шагов

    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden', !modal.classList.contains('hidden'));
    }

    function addIngredient() {
        const container = document.getElementById('ingredientList');
        const newIndex = ingredientCount;
        const ingredientHtml = `
            <div class="ingredient-item grid grid-cols-12 gap-3">
                <div class="col-span-5">
                    <input type="text" name="ingredients[${newIndex}][name]" placeholder="Название" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                </div>
                <div class="col-span-3">
                    <input type="number" name="ingredients[${newIndex}][quantity]" placeholder="Количество" min="0" step="0.1" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                </div>
                <div class="col-span-3">
                    <select name="ingredients[${newIndex}][unit]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
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
                <div class="col-span-1 flex items-center">
                    <button type="button" onclick="removeIngredient(this)"
                        class="text-red-500 hover:text-red-700 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', ingredientHtml);
        ingredientCount++;
    }

    function removeIngredient(button) {
        const ingredientItem = button.closest('.ingredient-item');
        if (ingredientItem) {
            ingredientItem.remove();
            reindexIngredients();
        }
    }

    function reindexIngredients() {
        const ingredients = document.querySelectorAll('#ingredientList .ingredient-item');
        ingredientCount = 0;
        ingredients.forEach((item, index) => {
            item.querySelectorAll('input, select').forEach(input => {
                const nameAttr = input.getAttribute('name');
                if (nameAttr) {
                    input.setAttribute('name', nameAttr.replace(/ingredients\[\d+\]/, `ingredients[${index}]`));
                }
            });
            ingredientCount++;
        });
    }

    function addStep() {
        const container = document.getElementById('stepsList');
        const newIndex = stepCount;
        const stepHtml = `
            <div class="step-item flex gap-3">
                <span class="text-lg font-bold mt-2">${newIndex + 1}.</span>
                <textarea name="steps[${newIndex}][description]" rows="2" placeholder="Подробно опишите этот шаг" required
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm h-10"></textarea>
                <button type="button" onclick="removeStep(this)"
                    class="ml-2 px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Удалить шаг</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', stepHtml);
        stepCount++;
    }

    function removeStep(button) {
        const stepItem = button.closest('.step-item');
        if (stepItem) {
            stepItem.remove();
            reindexSteps();
        }
    }

    function reindexSteps() {
        const steps = document.querySelectorAll('#stepsList .step-item');
        stepCount = 0;
        steps.forEach((item, index) => {
            item.querySelector('span').textContent = `${index + 1}.`;
            item.querySelector('textarea').name = `steps[${index}][description]`;
            stepCount++;
        });
    }

    // Инициализация модального окна добавления рецепта при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация, если нет старых значений ингредиентов, добавляем один по умолчанию
        if (document.querySelectorAll('#ingredientList .ingredient-item').length === 0) {
            addIngredient();
        }
        // Инициализация, если нет старых значений шагов, добавляем один по умолчанию
        if (document.querySelectorAll('#stepsList .step-item').length === 0) {
            addStep();
        }
    });

    // Обновление toggleModal для сброса формы при открытии/закрытии
    const originalToggleModal = window.toggleModal;
    window.toggleModal = function(modalId) {
        originalToggleModal(modalId);
        if (modalId === 'addRecipeModal' && !document.getElementById(modalId).classList.contains('hidden')) {
            // Модальное окно открывается, сбрасываем форму
            document.getElementById('addRecipeForm').reset();
            // Очищаем и заново инициализируем поля ингредиентов и шагов
            document.getElementById('ingredientList').innerHTML = '';
            document.getElementById('stepsList').innerHTML = '';
            ingredientCount = 0; // Сбрасываем счетчик
            stepCount = 0; // Сбрасываем счетчик
            addIngredient();
            addStep();
        }
    };

    // Закрытие уведомлений
    setTimeout(() => {
        const successAlert = document.getElementById('alert-success');
        const errorAlert = document.getElementById('alert-error');
        
        if (successAlert) successAlert.remove();
        if (errorAlert) errorAlert.remove();
    }, 5000);
</script>
</x-app-layout>

