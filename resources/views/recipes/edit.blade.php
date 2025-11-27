<x-app-layout>
    <div class="container mx-auto py-12">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-6 text-center">Редактировать рецепт</h1>

            <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Название рецепта:</label>
                    <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('title', $recipe->title) }}" required>
                    @error('title')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Описание:</label>
                    <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32" required>{{ old('description', $recipe->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Категория:</label>
                    <select name="category_id" id="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $recipe->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="calories" class="block text-gray-700 text-sm font-bold mb-2">Калории (ккал):</label>
                        <input type="number" name="calories" id="calories" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('calories', $recipe->calories) }}" required min="0">
                        @error('calories')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="servings" class="block text-gray-700 text-sm font-bold mb-2">Количество порций:</label>
                        <input type="number" name="servings" id="servings" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('servings', $recipe->servings) }}" required min="1">
                        @error('servings')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="difficulty" class="block text-gray-700 text-sm font-bold mb-2">Сложность:</label>
                        <select name="difficulty" id="difficulty" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="easy" {{ old('difficulty', $recipe->difficulty) == 'easy' ? 'selected' : '' }}>Легко</option>
                            <option value="medium" {{ old('difficulty', $recipe->difficulty) == 'medium' ? 'selected' : '' }}>Средне</option>
                            <option value="hard" {{ old('difficulty', $recipe->difficulty) == 'hard' ? 'selected' : '' }}>Сложно</option>
                        </select>
                        @error('difficulty')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="proteins" class="block text-gray-700 text-sm font-bold mb-2">Белки (г):</label>
                        <input type="number" name="proteins" id="proteins" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('proteins', $recipe->proteins) }}" step="0.1">
                        @error('proteins')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="fats" class="block text-gray-700 text-sm font-bold mb-2">Жиры (г):</label>
                        <input type="number" name="fats" id="fats" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('fats', $recipe->fats) }}" step="0.1">
                        @error('fats')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="carbs" class="block text-gray-700 text-sm font-bold mb-2">Углеводы (г):</label>
                        <input type="number" name="carbs" id="carbs" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('carbs', $recipe->carbs) }}" step="0.1">
                        @error('carbs')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Основное изображение рецепта:</label>
                    @if($recipe->image_url)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $recipe->image_url) }}" alt="Текущее изображение" class="w-32 h-32 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept="image/*">
                    @error('image')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ingredients Section --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ингредиенты:</label>
                    <div id="ingredients-container">
                        @foreach(old('ingredients', $recipe->ingredients) as $index => $ingredient)
                            <div class="ingredient-item mb-2 p-2 border rounded bg-gray-50 flex items-center">
                                <input type="hidden" name="ingredients[{{ $index }}][id]" value="{{ $ingredient->id ?? '' }}">
                                <input type="text" name="ingredients[{{ $index }}][name]" class="shadow appearance-none border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline flex-1 mr-2" placeholder="Название" value="{{ old('ingredients.' . $index . '.name', $ingredient->name ?? '') }}" required>
                                <input type="number" name="ingredients[{{ $index }}][quantity]" class="shadow appearance-none border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-20 mr-2" placeholder="Кол-во" value="{{ old('ingredients.' . $index . '.quantity', $ingredient->quantity ?? '') }}" required step="0.1">
                                <select name="ingredients[{{ $index }}][unit]" class="shadow appearance-none border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-24 mr-2" required>
                                    <option value="г" {{ old('ingredients.' . $index . '.unit', $ingredient->unit ?? '') == 'г' ? 'selected' : '' }}>г</option>
                                    <option value="мл" {{ old('ingredients.' . $index . '.unit', $ingredient->unit ?? '') == 'мл' ? 'selected' : '' }}>мл</option>
                                    <option value="шт" {{ old('ingredients.' . $index . '.unit', $ingredient->unit ?? '') == 'шт' ? 'selected' : '' }}>шт</option>
                                    <option value="стакан" {{ old('ingredients.' . $index . '.unit', $ingredient->unit ?? '') == 'стакан' ? 'selected' : '' }}>стакан</option>
                                    <option value="ложка" {{ old('ingredients.' . $index . '.unit', $ingredient->unit ?? '') == 'ложка' ? 'selected' : '' }}>ложка</option>
                                    <option value="по вкусу" {{ old('ingredients.' . $index . '.unit', $ingredient->unit ?? '') == 'по вкусу' ? 'selected' : '' }}>по вкусу</option>
                                </select>
                                <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700">Удалить</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addIngredient()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mt-2">Добавить ингредиент</button>
                     @error('ingredients')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                     @error('ingredients.*.name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                     @error('ingredients.*.quantity')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                     @error('ingredients.*.unit')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Steps Section --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Шаги приготовления:</label>
                    <div id="steps-container">
                        @foreach(old('steps', $recipe->steps) as $index => $step)
                            <div class="step-item mb-4 p-4 border rounded-lg bg-white">
                                <input type="hidden" name="steps[{{ $index }}][id]" value="{{ $step->id ?? '' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold">Шаг <span class="step-number">{{ $index + 1 }}</span></h3>
                                    <button type="button" onclick="removeStep(this)" class="text-red-500 hover:text-red-700">Удалить</button>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Описание шага:</label>
                                    <textarea name="steps[{{ $index }}][description]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('steps.' . $index . '.description', $step->description ?? '') }}</textarea>
                                     @error('steps.' . $index . '.description')
                                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addStep()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Добавить шаг</button>
                     @error('steps')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 transition duration-200">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>

<script>
    let ingredientCount = {{ count(old('ingredients', $recipe->ingredients)) }};
    let stepCount = {{ count(old('steps', $recipe->steps)) }};

    function addIngredient() {
        const container = document.getElementById('ingredients-container');
        const ingredientDiv = document.createElement('div');
        ingredientDiv.className = 'ingredient-item mb-2 p-2 border rounded bg-gray-50 flex items-center';
        ingredientDiv.innerHTML = `
            <input type="hidden" name="ingredients[${ingredientCount}][id]" value="">
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
            <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700">Удалить</button>
        `;
        container.appendChild(ingredientDiv);
        ingredientCount++;
    }

    function removeIngredient(button) {
        button.parentElement.remove();
         reindexIngredients();
    }

    function reindexIngredients() {
         const ingredients = document.querySelectorAll('#ingredients-container .ingredient-item');
         ingredientCount = 0;
         ingredients.forEach((item, index) => {
             item.querySelector('input[name^="ingredients"][name$="[id]"]').name = `ingredients[${index}][id]`;
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
            <input type="hidden" name="steps[${stepCount}][id]" value="">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-lg font-semibold">Шаг <span class="step-number">${stepCount + 1}</span></h3>
                <button type="button" onclick="removeStep(this)" class="text-red-500 hover:text-red-700">Удалить</button>
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
             item.querySelector('input[name^="steps"][name$="[delete_image]"]');
             stepCount++;
         });
     }

    // Initial population and reindexing on load
    document.addEventListener('DOMContentLoaded', function() {
        reindexIngredients();
        reindexSteps();

         // Add event listeners for existing remove buttons after reindexing
         document.querySelectorAll('.ingredient-item button').forEach(button => {
             button.onclick = function() { removeIngredient(this); };
         });

         document.querySelectorAll('.step-item button').forEach(button => {
             button.onclick = function() { removeStep(this); };
         });
    });

</script>

</x-app-layout> 