<!-- Модальное окно для отклонения рецепта -->
<div id="rejectModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-gray-900 bg-opacity-50">
    <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
        <h3 class="text-lg font-bold mb-4">Отклонить рецепт</h3>
        <form id="rejectForm" action="" method="POST">
            @csrf
            <div class="mb-4">
                <label for="reason" class="block font-semibold">Причина отклонения:</label>
                <textarea name="reason" id="reason" rows="3" class="border rounded w-full p-2" required
                    minlength="10"></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">
                    Отклонить
                </button>
                <button type="button" onclick="closeRejectModal()"
                    class="bg-gray-300 text-gray-700 py-2 px-4 rounded hover:bg-gray-400">
                    Отмена
                </button>
            </div>
        </form>
    </div>
</div> 