<!-- Модальное окно для редактирования ингредиента -->
<div id="editIngredientModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <form id="editIngredientForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium">Название ингредиента</label>
                <input type="text" name="name" id="edit_name"
                    class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div class="mb-4">
                <label for="edit_quantity" class="block text-sm font-medium">Количество</label>
                <input type="text" name="quantity" id="edit_quantity"
                    class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" required>
                <p id="editQuantityError" class="text-red-500 text-sm hidden">Пожалуйста, введите только цифры в
                    поле "Количество".</p>
            </div>
            <div class="mb-4">
                <label for="edit_unit" class="block text-sm font-medium">Единица измерения</label>
                <select name="unit" id="edit_unit"
                    class="mt-1 block w-full border rounded p-2 focus:ring-2 focus:ring-blue-400" required>
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
            <div class="flex space-x-2">
                <button type="submit"
                    class="bg-teal-600 text-white py-2 px-4 rounded w-full hover:bg-teal-700 transition-colors">Обновить</button>
                <button type="button" onclick="toggleModal('editIngredientModal')"
                    class="bg-gray-500 text-white py-2 px-4 rounded w-full hover:bg-gray-600 transition-colors">Закрыть</button>
            </div>
        </form>
    </div>
</div> 