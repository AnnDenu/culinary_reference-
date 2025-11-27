<x-app-layout>
    <div class="bg-gradient-to-r from-yellow-100 via-orange-100 to-yellow-200">
    <div class="max-w-7xl mx-auto py-6 ">
        <!-- Заголовок страницы -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">Управление комментариями</h1>
            <a href="{{ route('admin.comments.export') }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Выгрузить в Excel
            </a>
        </div>

        <!-- Сообщение об успехе -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Таблица комментариев -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="min-w-full bg-white">
                <thead class="bg-yellow-300 text-gray-600 uppercase text-sm leading-normal">
                    <tr>
                        <th class="py-3 px-6 text-left">Пользователь</th>
                        <th class="py-3 px-6 text-left">Комментарий</th>
                        <th class="py-3 px-6 text-left">Рецепт</th>
                        <th class="py-3 px-6 text-left">Дата</th>
                        <th class="py-3 px-6 text-center">Действия</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    @foreach ($comments as $comment)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="font-medium">{{ $comment->user->username }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <p class="text-gray-600">{{ Str::limit($comment->comment, 50) }}</p>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <a href="{{ route('recipes.show', $comment->recipe->id) }}" class="text-blue-500 underline">
                                    {{ $comment->recipe->title }}
                                </a>
                            </td>
                            <td class="py-3 px-6 text-left">
                                {{ $comment->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="py-3 px-6 text-center">
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Удалить комментарий?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded-full">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Пагинация -->
        <div class="mt-6">
            {{ $comments->links() }}
        </div>
    </div>
    </div>
</x-app-layout>
