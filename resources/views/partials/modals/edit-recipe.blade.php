<!-- Модальное окно для редактирования рецепта -->
<div id="editRecipeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-4xl mx-auto shadow-xl overflow-y-auto max-h-screen">
        <h2 class="text-xl font-bold mb-4">Редактировать рецепт</h2>
        <form id="editRecipeForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="recipe_id" id="edit_recipe_id">
            <div class="mb-4">
                <label for="edit_title" class="block text-sm font-medium">Название рецепта</label>
                <input type="text" name="title" id="edit_title" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div class="mb-4">
                <label for="edit_description" class="block text-sm font-medium">Описание</label>
                <textarea name="description" id="edit_description" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" required></textarea>
            </div>
            <div class="mb-4">
                <label for="edit_image" class="block text-sm font-medium">Изображение рецепта</label>
                <input type="file" name="image" id="edit_image" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label for="edit_proteins" class="block text-sm font-medium">Белки (г)</label>
                <input type="number" name="proteins" id="edit_proteins" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" step="0.01">
            </div>
            <div class="mb-4">
                <label for="edit_fats" class="block text-sm font-medium">Жиры (г)</label>
                <input type="number" name="fats" id="edit_fats" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" step="0.01">
            </div>
            <div class="mb-4">
                <label for="edit_carbs" class="block text-sm font-medium">Углеводы (г)</label>
                <input type="number" name="carbs" id="edit_carbs" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" step="0.01">
            </div>
            <div class="mb-4">
                <label for="edit_cooking_time" class="block text-sm font-medium">Время приготовления (мин)</label>
                <input type="number" name="cooking_time" id="edit_cooking_time" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div class="mb-4">
                <label for="edit_calories" class="block text-sm font-medium">Калории</label>
                <input type="number" name="calories" id="edit_calories" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div class="mb-4">
                <label for="edit_servings" class="block text-sm font-medium">Количество порций</label>
                <input type="number" name="servings" id="edit_servings" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div class="mb-4">
                <label for="edit_difficulty" class="block text-sm font-medium">Сложность</label>
                <select name="difficulty" id="edit_difficulty" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" required>
                    <option value="easy">Легкий</option>
                    <option value="medium">Средний</option>
                    <option value="hard">Сложный</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="edit_category_id" class="block text-sm font-medium">Категория</label>
                <select name="category_id" id="edit_category_id" class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-teal-600 text-white py-2 px-4 rounded w-full hover:bg-teal-700 transition-colors">Сохранить</button>
                <button type="button" onclick="toggleModal('editRecipeModal')" class="bg-gray-500 text-white py-2 px-4 rounded w-full hover:bg-gray-600 transition-colors">Закрыть</button>
            </div>
        </form>
    </div>
</div> 