<x-app-layout>
    <div class="container mx-auto py-12">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-6 text-center">Создать новый рецепт</h1>

            <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Название рецепта:</label>
                    <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('title') }}" required>
                    @error('title')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Описание:</label>
                    <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Категория:</label>
                    <select name="category_id" id="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="calories" class="block text-gray-700 text-sm font-bold mb-2">Калории (ккал):</label>
                        <input type="number" name="calories" id="calories" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('calories') }}" required min="0">
                        @error('calories')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="servings" class="block text-gray-700 text-sm font-bold mb-2">Количество порций:</label>
                        <input type="number" name="servings" id="servings" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('servings') }}" required min="1">
                        @error('servings')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="difficulty" class="block text-gray-700 text-sm font-bold mb-2">Сложность:</label>
                        <select name="difficulty" id="difficulty" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Легко</option>
                            <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Средне</option>
                            <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Сложно</option>
                        </select>
                        @error('difficulty')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="proteins" class="block text-gray-700 text-sm font-bold mb-2">Белки (г):</label>
                        <input type="number" name="proteins" id="proteins" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('proteins') }}" step="0.1">
                        @error('proteins')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="fats" class="block text-gray-700 text-sm font-bold mb-2">Жиры (г):</label>
                        <input type="number" name="fats" id="fats" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('fats') }}" step="0.1">
                        @error('fats')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="carbs" class="block text-gray-700 text-sm font-bold mb-2">Углеводы (г):</label>
                        <input type="number" name="carbs" id="carbs" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('carbs') }}" step="0.1">
                        @error('carbs')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Основное изображение рецепта:</label>
                    <input type="file" name="image" id="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept="image/*" required>
                    @error('image')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ingredients Section --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ингредиенты:</label>
                    <div id="ingredients-container">
                        @if(old('ingredients'))
                            @foreach(old('ingredients') as $index => $ingredient)
                                <div class="ingredient-item mb-2 p-2 border rounded bg-gray-50 flex items-center">
                                    <input type="text" name="ingredients[{{ $index }}][name]" class="shadow appearance-none border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline flex-1 mr-2" placeholder="Название" value="{{ $ingredient['name'] ?? '' }}" required>
                                    <input type="number" name="ingredients[{{ $index }}][quantity]" class="shadow appearance-none border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-20 mr-2" placeholder="Кол-во" value="{{ $ingredient['quantity'] ?? '' }}" required step="0.1">
                                    <select name="ingredients[{{ $index }}][unit]" class="shadow appearance-none border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-24 mr-2" required>
                                        <option value="г" {{ ($ingredient['unit'] ?? '') == 'г' ? 'selected' : '' }}>г</option>
                                        <option value="мл" {{ ($ingredient['unit'] ?? '') == 'мл' ? 'selected' : '' }}>мл</option>
                                        <option value="шт" {{ ($ingredient['unit'] ?? '') == 'шт' ? 'selected' : '' }}>шт</option>
                                        <option value="стакан" {{ ($ingredient['unit'] ?? '') == 'стакан' ? 'selected' : '' }}>стакан</option>
                                        <option value="ложка" {{ ($ingredient['unit'] ?? '') == 'ложка' ? 'selected' : '' }}>ложка</option>
                                        <option value="по вкусу" {{ ($ingredient['unit'] ?? '') == 'по вкусу' ? 'selected' : '' }}>по вкусу</option>
                                    </select>
                                    <button type="button" onclick="removeIngredient(this)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Удалить</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick="addIngredient()" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 mt-2 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Добавить ингредиент
                    </button>
                    @error('ingredients')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Steps Section --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Шаги приготовления:</label>
                    <div id="steps-container">
                        @if(old('steps'))
                            @foreach(old('steps') as $index => $step)
                                <div class="step-item mb-4 p-4 border rounded-lg bg-white">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-semibold">Шаг {{ $index + 1 }}</h3>
                                        <button type="button" onclick="removeStep(this)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Удалить</button>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Описание шага:</label>
                                        <textarea name="steps[{{ $index }}][description]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ $step['description'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick="addStep()" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Добавить шаг
                    </button>
                    @error('steps')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 transition duration-200">Создать рецепт</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let ingredientCount = {{ count(old('ingredients', [])) }};
        let stepCount = {{ count(old('steps', [])) }};

        function addIngredient() {
            const container = document.getElementById('ingredients-container');
            const ingredientDiv = document.createElement('div');
            ingredientDiv.className = 'ingredient-item mb-2 p-2 border rounded bg-gray-50 flex items-center';
            ingredientDiv.innerHTML = `
                <input type="text" name="ingredients[${ingredientCount}][name]" class="shadow appearance-none border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline flex-1 mr-2" placeholder="Название" required>
                <input type="number" name="ingredients[${ingredientCount}][quantity]" class="shadow appearance-none border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-20 mr-2" placeholder="Кол-во" required step="0.1">
                <select name="ingredients[${ingredientCount}][unit]" class="shadow appearance-none border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-24 mr-2" required>
                    <option value="г">г</option>
                    <option value="мл">мл</option>
                    <option value="шт">шт</option>
                    <option value="стакан">стакан</option>
                    <option value="ложка">ложка</option>
                    <option value="по вкусу">по вкусу</option>
                </select>
                <button type="button" onclick="removeIngredient(this)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Удалить</button>
            `;
            container.appendChild(ingredientDiv);
            ingredientCount++;
        }

        function removeIngredient(button) {
            const ingredientDiv = button.closest('.ingredient-item');
            console.log('Attempting to remove ingredient div:', ingredientDiv);
            if (ingredientDiv) {
                ingredientDiv.parentNode.removeChild(ingredientDiv);
            }
            reindexIngredients();
        }

        function reindexIngredients() {
            const ingredients = document.querySelectorAll('#ingredients-container .ingredient-item');
            ingredientCount = 0;
            ingredients.forEach((item, index) => {
                item.querySelector('input[name^="ingredients"][name$="[name]"]').name = `ingredients[${index}][name]`;
                item.querySelector('input[name^="ingredients"][name$="[quantity]"]').name = `ingredients[${index}][quantity]`;
                item.querySelector('select[name^="ingredients"][name$="[unit]"]').name = `ingredients[${index}][unit]`;
                ingredientCount++;
            });
        }

        function addStep() {
            const container = document.getElementById('steps-container');
            const stepDiv = document.createElement('div');
            stepDiv.className = 'step-item mb-4 p-4 border rounded-lg bg-white';
            stepDiv.innerHTML = `
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-semibold">Шаг ${stepCount + 1}</h3>
                    <button type="button" onclick="removeStep(this)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Удалить</button>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Описание шага:</label>
                    <textarea name="steps[${stepCount}][description]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                </div>
            `;
            container.appendChild(stepDiv);
            stepCount++;
            reindexSteps();
        }

        function removeStep(button) {
            const stepDiv = button.closest('.step-item');
            if (stepDiv) {
                stepDiv.parentNode.removeChild(stepDiv);
            }
            reindexSteps();
        }

        function reindexSteps() {
            const steps = document.querySelectorAll('#steps-container .step-item');
            stepCount = 0;
            steps.forEach((item, index) => {
                item.querySelector('h3').textContent = `Шаг ${index + 1}`;
                item.querySelector('textarea').name = `steps[${index}][description]`;
                stepCount++;
            });
        }

        // Инициализация первого ингредиента и шага при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            if (ingredientCount === 0) {
                addIngredient();
            }
            if (stepCount === 0) {
                addStep();
            }
        });
    </script>
</x-app-layout> 