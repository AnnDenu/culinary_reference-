<x-app-layout>
    <div class="bg-gradient-to-r from-yellow-100 via-orange-100 to-yellow-200">
        <div class="container mx-auto p-6">
            <h1 class="text-2xl font-bold mb-4">Управление категориями</h1>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.store') }}" method="POST" class="mb-6">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium">Название категории</label>
                    <input type="text" name="name" id="name" class="border border-gray-300 rounded w-full p-2" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium">Описание категории</label>
                    <textarea name="description" id="description" class="border border-gray-300 rounded w-full p-2"
                              rows="3"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Создать
                    категорию</button>
            </form>

            <h2 class="text-xl font-semibold mt-4">Существующие категории</h2>
            <ul class="list-group">
                @foreach ($categories as $category)
                    <li class="flex justify-between items-center bg-white border-b p-4">
                        <div>
                            <div class="text-lg">{{ $category->name }}</div>
                            <div class="text-gray-500">{{ $category->description }}</div>
                        </div>
                        <div>
                            <button
                                onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')"
                                class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">Редактировать</button>
                            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                                  class="inline-block" onsubmit="return confirm('Вы уверены, что хотите удалить?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Удалить</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            <!-- Модальное окно -->
            <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
                <div class="bg-white p-6 rounded-lg w-96">
                    <h2 class="text-xl font-semibold mb-4">Редактировать категорию</h2>
                    <form id="editForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="editName" class="block text-sm font-medium">Название категории</label>
                            <input type="text" name="name" id="editName" class="border border-gray-300 rounded w-full p-2"
                                   required>
                        </div>
                        <div class="mb-4">
                            <label for="editDescription" class="block text-sm font-medium">Описание категории</label>
                            <textarea name="description" id="editDescription"
                                      class="border border-gray-300 rounded w-full p-2" rows="3"></textarea>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Сохранить
                            изменения</button>
                        <button type="button" onclick="closeEditModal()"
                                class="bg-gray-300 text-gray-800 py-2 px-4 rounded hover:bg-gray-400">Отменить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openEditModal(id, name, description) {
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editForm').action = '/admin/categories/' + id; // Укажите правильный маршрут
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

</x-app-layout>
