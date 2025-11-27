<!-- Модальное окно для редактирования шага -->
<div id="editStepModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <form id="editStepForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_step_description" class="block text-sm font-medium">Описание шага</label>
                <textarea name="description" id="edit_step_description" rows="3"
                    class="mt-1 block w-full border rounded p-2" required></textarea>
            </div>
            <div class="flex space-x-2">
                <button type="submit"
                    class="bg-teal-600 text-white py-2 px-4 rounded w-full hover:bg-teal-700 transition-colors">Обновить</button>
                <button type="button" onclick="toggleModal('editStepModal')"
                    class="bg-gray-500 text-white py-2 px-4 rounded w-full hover:bg-gray-600 transition-colors">Закрыть</button>
            </div>
        </form>
    </div>
</div> 