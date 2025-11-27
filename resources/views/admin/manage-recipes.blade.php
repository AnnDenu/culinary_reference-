<!-- resources/views/recipes/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Управление рецептами') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-r from-yellow-100 via-orange-100 to-yellow-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-bold mb-6">Рецепты на модерации</h2>
                    
                    @if($recipes->isEmpty())
                        <p class="text-center text-gray-600">Нет рецептов, ожидающих модерации</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($recipes as $recipe)
                                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                                    <div class="relative">
                                        <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}"
                                             class="w-full h-48 object-cover">
                                        <!-- Бейдж для отредактированных рецептов -->
                                        @if($recipe->updated_at != $recipe->created_at)
                                            <span class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-sm">
                                                Отредактирован
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold mb-2 text-yellow-600">{{ $recipe->title }}</h3>
                                        <p class="text-gray-700 mb-4">{{ $recipe->description }}</p>

                                        <!-- Информация о рецепте -->
                                        <div class="mb-4 grid grid-cols-2 gap-2 text-sm">
                                            <div class="text-gray-600">
                                                <span class="font-medium">Время:</span> {{ $recipe->cooking_time }} мин
                                            </div>
                                            <div class="text-gray-600">
                                                <span class="font-medium">Калории:</span> {{ $recipe->calories }}
                                            </div>
                                            <div class="text-gray-600 text-sm mb-1">
                                                <span class="font-medium">Порции:</span> 
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
                                            </div>
                                            <div class="text-gray-600">
                                                <span class="font-medium">Сложность:</span> {{ $recipe->difficulty == 'easy' ? 'Легко' : ($recipe->difficulty == 'medium' ? 'Средне' : 'Сложно') }}
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <h4 class="text-lg font-semibold text-yellow-600 mb-2">Ингредиенты:</h4>
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach ($recipe->ingredients as $ingredient)
                                                    <li class="text-gray-700">
                                                        <span class="font-medium">{{ $ingredient->name }}</span>
                                                        — {{ $ingredient->quantity }} {{ $ingredient->unit }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <!-- Информация об авторе и времени -->
                                        <div class="mb-4 text-sm text-gray-600">
                                            <p>Автор: {{ $recipe->user->username }}</p>
                                            <p>Отправлено: {{ $recipe->created_at->setTimezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</p>
                                            @if($recipe->updated_at != $recipe->created_at)
                                                <p>Отредактировано: {{ $recipe->updated_at->setTimezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</p>
                                            @endif
                                        </div>

                                        <div class="mb-4">
                                            <h4 class="text-lg font-semibold text-yellow-600 mb-2">Шаги:</h4>
                                            <ol class="list-decimal pl-5 space-y-1">
                                                @foreach ($recipe->steps as $step)
                                                    <li class="text-gray-700">
                                                        {{ $step->description }}
                                                    </li>
                                                @endforeach
                                            </ol>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <form action="{{ route('admin.recipes.approve', $recipe) }}" method="POST"
                                                  class="inline-block">
                                                @csrf
                                                <button type="submit"
                                                        class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                                                    Одобрить
                                                </button>
                                            </form>
                                            <button onclick="openRejectModal({{ $recipe->id }})"
                                                    class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">
                                                Отклонить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для отклонения рецепта -->
    <div id="rejectModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
            <h3 class="text-lg font-bold mb-4">Отклонить рецепт</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block font-semibold">Причина отклонения:</label>
                    <textarea 
                        name="rejection_reason" 
                        id="rejection_reason" 
                        rows="3" 
                        class="border rounded w-full p-2" 
                        required 
                        minlength="10"
                    ></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" 
                        class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">
                        Отклонить
                    </button>
                    <button type="button" 
                        onclick="closeRejectModal()"
                        class="bg-gray-300 text-gray-700 py-2 px-4 rounded hover:bg-gray-400">
                        Отмена
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Модальное окно для редактирования рецепта -->
    <div id="editRecipeModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
            <h3 class="text-lg font-bold mb-4">Редактировать рецепт</h3>
            <form id="editRecipeForm" method="POST">
                @csrf
                <input type="hidden" name="recipe_id" id="recipe_id">
                <div class="mb-4">
                    <label for="title" class="block font-semibold">Название:</label>
                    <input type="text" name="title" id="title" class="border rounded w-full p-2" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block font-semibold">Описание:</label>
                    <textarea name="description" id="description" rows="3" class="border rounded w-full p-2" required></textarea>
                </div>
                <h4 class="text-lg font-semibold text-yellow-600 mb-2">Шаги:</h4>
                <ol class="list-decimal pl-5 space-y-1" id="stepsList">
                    <!-- Шаги будут добавлены здесь через JavaScript -->
                </ol>
                <button type="button" onclick="addStep()" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Добавить шаг</button>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Сохранить</button>
                    <button type="button" onclick="closeEditRecipeModal()" class="bg-gray-300 text-gray-700 py-2 px-4 rounded hover:bg-gray-400">Отмена</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(recipeId) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            
            // Очищаем предыдущий текст причины
            document.getElementById('rejection_reason').value = '';
            
            // Устанавливаем правильный URL для формы
            form.action = `/admin/recipes/${recipeId}/reject`;
            
            modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.add('hidden');
        }

        // Добавляем обработчик отправки формы
        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            const reason = document.getElementById('rejection_reason').value.trim();
            
            if (reason.length < 10) {
                e.preventDefault();
                alert('Причина отклонения должна содержать минимум 10 символов');
                return false;
            }

            // Добавляем индикатор загрузки
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Отправка...';
        });

        function openEditRecipeModal(recipe) {
            const modal = document.getElementById('editRecipeModal');
            document.getElementById('recipe_id').value = recipe.id;
            document.getElementById('title').value = recipe.title;
            document.getElementById('description').value = recipe.description;

            // Очистите список шагов
            const stepsList = document.getElementById('stepsList');
            stepsList.innerHTML = '';

            recipe.steps.forEach(step => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <input type="text" value="${step.description}" class="border rounded w-full p-1 mb-1" data-step-id="${step.id}">
                    <button type="button" onclick="removeStep(this)">Удалить</button>
                `;
                stepsList.appendChild(li);
            });

            modal.classList.remove('hidden');
        }

        function closeEditRecipeModal() {
            const modal = document.getElementById('editRecipeModal');
            modal.classList.add('hidden');
        }

        function addStep() {
            const stepsList = document.getElementById('stepsList');
            const li = document.createElement('li');
            li.innerHTML = `
                <input type="text" value="" class="border rounded w-full p-1 mb-1" placeholder="Описание шага">
                <button type="button" onclick="removeStep(this)">Удалить</button>
            `;
            stepsList.appendChild(li);
        }

        function removeStep(button) {
            const li = button.parentElement;
            li.remove();
        }
    </script>
</x-app-layout>
