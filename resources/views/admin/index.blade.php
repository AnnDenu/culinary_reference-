<x-app-layout>
<div class="py-12 bg-gradient-to-r from-yellow-100 via-orange-100 to-yellow-200">
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold text-center mb-8">Админ Панель</h1>

        <div class="flex justify-center space-x-4 mb-8">
            <!-- Управление категориями -->
            <a href="{{ route('admin.categories.index') }}"
                class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-600 transition duration-200">
                Управление категориями
            </a>

            <!-- Управление пользователями -->
            <a href="{{ route('admin.users.index') }}"
                class="bg-green-500 text-white px-6 py-3 rounded-lg shadow hover:bg-green-600 transition duration-200">
                Управление пользователями
            </a>

            <!-- Модерация рецептов -->
            <a href="{{ route('admin.recipes.index') }}"
                class="bg-yellow-500 text-white px-6 py-3 rounded-lg shadow hover:bg-yellow-600 transition duration-200">
                Модерация рецептов
            </a>

            <!-- Управление комментариями -->
            <a href="{{ route('admin.comments.index') }}"
                class="bg-yellow-500 text-white px-6 py-3 rounded-lg shadow hover:bg-yellow-600 transition duration-200">
                Управление комментариями
            </a>

            <!-- Модерация ингредиентов -->
            @if(auth()->user() && auth()->user()->isAdmin())
                <a href="{{ route('admin.ingredients.index') }}"
                    class="bg-purple-500 text-white px-6 py-3 rounded-lg shadow hover:bg-purple-600 transition duration-200">
                  Управление ингредиентами
                </a>
            @endif
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Отчеты по дате</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Начальная дата:</label>
                    <input type="date" id="start_date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Конечная дата:</label>
                    <input type="date" id="end_date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-4">
                <button onclick="downloadReport('{{ route('admin.export.comments') }}')"
                        class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-600 transition duration-200">
                    Скачать отчет по комментариям
                </button>
                <button onclick="downloadReport('{{ route('admin.export.user-actions') }}')"
                        class="bg-green-500 text-white px-6 py-3 rounded-lg shadow hover:bg-green-600 transition duration-200">
                    Скачать отчет по действиям пользователей
                </button>
                <button onclick="downloadReport('{{ route('admin.export.recipes') }}')"
                        class="bg-yellow-500 text-white px-6 py-3 rounded-lg shadow hover:bg-yellow-600 transition duration-200">
                    Скачать отчет по рецептам
                </button>
                <button onclick="downloadReport('{{ route('admin.export.recipe-views') }}')"
                        class="bg-purple-500 text-white px-6 py-3 rounded-lg shadow hover:bg-purple-600 transition duration-200">
                    Скачать отчет по просмотрам рецептов
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function downloadReport(route) {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    let url = route;
    const params = new URLSearchParams();

    if (startDate) {
        params.append('start_date', startDate);
    }
    if (endDate) {
        params.append('end_date', endDate);
    }

    if (params.toString()) {
        url = url + '?' + params.toString();
    }

    window.location.href = url;
}
</script>

</x-app-layout>
