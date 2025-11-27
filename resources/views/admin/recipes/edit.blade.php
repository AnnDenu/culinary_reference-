<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <!-- Заголовок страницы -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">
                    Редактирование рецепта
                </h1>
                <p class="mt-2 text-gray-600">
                    Внесите необходимые изменения в рецепт
                </p>
            </div>

            <!-- Форма редактирования -->
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        <form action="{{ route('recipes.update', $recipe->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Основная информация -->
                            <div class="space-y-6">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                                        Основная информация
                                    </h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Название рецепта -->
                                        <div class="col-span-2">
                                            <label for="title" class="block text-sm font-medium text-gray-700">
                                                Название рецепта
                                            </label>
                                            <input type="text" 
                                                   name="title" 
                                                   id="title" 
                                                   value="{{ $recipe->title }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" 
                                                   required>
                                        </div>

                                        <!-- Время приготовления -->
                                        <div>
                                            <label for="cooking_time" class="block text-sm font-medium text-gray-700">
                                                Время приготовления
                                            </label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="number" 
                                                       name="cooking_time" 
                                                       id="cooking_time" 
                                                       value="{{ $recipe->cooking_time }}"
                                                       class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-red-500 focus:ring-red-500" 
                                                       required>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <span class="text-gray-500 sm:text-sm">мин</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Калорийность -->
                                        <div>
                                            <label for="calories" class="block text-sm font-medium text-gray-700">
                                                Калорийность
                                            </label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="number" 
                                                       name="calories" 
                                                       id="calories" 
                                                       value="{{ $recipe->calories }}"
                                                       class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-red-500 focus:ring-red-500" 
                                                       required>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <span class="text-gray-500 sm:text-sm">ккал</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Порции -->
                                        <div>
                                            <label for="servings" class="block text-sm font-medium text-gray-700">
                                                Количество порций
                                            </label>
                                            <input type="number" 
                                                   name="servings" 
                                                   id="servings" 
                                                   value="{{ $recipe->servings }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" 
                                                   required>
                                        </div>

                                        <!-- Сложность -->
                                        <div>
                                            <label for="difficulty" class="block text-sm font-medium text-gray-700">
                                                Сложность приготовления
                                            </label>
                                            <select name="difficulty" 
                                                    id="difficulty" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                                <option value="easy" {{ $recipe->difficulty == 'easy' ? 'selected' : '' }}>Легко</option>
                                                <option value="medium" {{ $recipe->difficulty == 'medium' ? 'selected' : '' }}>Средне</option>
                                                <option value="hard" {{ $recipe->difficulty == 'hard' ? 'selected' : '' }}>Сложно</option>
                                            </select>
                                        </div>

                                        <!-- URL изображения -->
                                        <div class="col-span-2">
                                            <label for="image_url" class="block text-sm font-medium text-gray-700">
                                                URL изображения
                                            </label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">
                                                    URL
                                                </span>
                                                <input type="text" 
                                                       name="image_url" 
                                                       id="image_url" 
                                                       value="{{ $recipe->image_url }}"
                                                       class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Пищевая ценность -->
                                <div class="mt-8">
                                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                                        Пищевая ценность (на порцию)
                                    </h2>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Белки -->
                                        <div>
                                            <label for="proteins" class="block text-sm font-medium text-gray-700">
                                                Белки (г)
                                            </label>
                                            <input type="number" 
                                                   name="proteins" 
                                                   id="proteins" 
                                                   value="{{ $recipe->proteins }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" 
                                                   step="0.1">
                                        </div>

                                        <!-- Жиры -->
                                        <div>
                                            <label for="fats" class="block text-sm font-medium text-gray-700">
                                                Жиры (г)
                                            </label>
                                            <input type="number" 
                                                   name="fats" 
                                                   id="fats" 
                                                   value="{{ $recipe->fats }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" 
                                                   step="0.1">
                                        </div>

                                        <!-- Углеводы -->
                                        <div>
                                            <label for="carbs" class="block text-sm font-medium text-gray-700">
                                                Углеводы (г)
                                            </label>
                                            <input type="number" 
                                                   name="carbs" 
                                                   id="carbs" 
                                                   value="{{ $recipe->carbs }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" 
                                                   step="0.1">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Шаги приготовления -->
                            <div class="mt-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                                    Шаги приготовления
                                </h2>
                                <div id="steps-container" class="space-y-6">
                                    @foreach($recipe->steps as $index => $step)
                                    <div class="step-item p-6 border rounded-lg bg-white shadow">
                                        <input type="hidden" name="steps[{{ $index }}][id]" value="{{ $step->id ?? '' }}">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-semibold text-gray-700">Шаг <span class="step-number">{{ $index + 1 }}</span></h3>
                                            <button type="button" onclick="removeStep(this)" class="text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="steps[{{ $index }}][description]" class="block text-sm font-medium text-gray-700">Описание шага:</label>
                                            <textarea name="steps[{{ $index }}][description]" rows="3" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('steps.' . $index . '.description', $step->description ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" onclick="addStep()" class="mt-4 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Добавить шаг
                                </button>
                            </div>

                            <!-- Кнопки действий -->
                            <div class="mt-8 flex justify-end space-x-4">
                                <a href="{{ route('recipes.index') }}" 
                                   class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    Отмена
                                </a>
                                <button type="submit" 
                                        class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    Сохранить изменения
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Предпросмотр изображения -->
                @if($recipe->image_url)
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Текущее изображение:</h3>
                    <div class="rounded-lg overflow-hidden shadow-md">
                        <img src="{{ $recipe->image_url }}" 
                             alt="{{ $recipe->title }}" 
                             class="w-full h-48 object-cover">
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let stepCount = {{ count(old('steps', $recipe->steps)) }};

    function addStep() {
        const container = document.getElementById('steps-container');
        const stepDiv = document.createElement('div');
        stepDiv.className = 'step-item p-6 border rounded-lg bg-white shadow';
        stepDiv.innerHTML = `
            <input type="hidden" name="steps[${stepCount}][id]" value="">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Шаг <span class="step-number">${stepCount + 1}</span></h3>
                <button type="button" onclick="removeStep(this)" class="text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <label for="steps[${stepCount}][description]" class="block text-sm font-medium text-gray-700">Описание шага:</label>
                <textarea name="steps[${stepCount}][description]" rows="3" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
            </div>
        `;
        container.appendChild(stepDiv);
        stepCount++;
        reindexSteps();
    }

    function removeStep(button) {
        button.closest('.step-item').remove();
        reindexSteps();
    }

    function reindexSteps() {
        const steps = document.querySelectorAll('#steps-container .step-item');
        stepCount = 0;
        steps.forEach((item, index) => {
            item.querySelector('.step-number').innerText = index + 1;
            item.querySelector('input[name^="steps"][name$="[id]"]').name = `steps[${index}][id]`;
            item.querySelector('textarea[name^="steps"][name$="[description]"]').name = `steps[${index}][description]`;
            stepCount++;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        reindexSteps();
    });
</script>
