<x-app-layout>

<div class="container mx-auto mt-8">
    <h1 class="text-3xl font-bold text-center mb-6">Отклоненные рецепты</h1>

    @if($notifications->isEmpty())
        <div class="text-center text-gray-500">
            <p>Нет отклоненных рецептов.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border-b p-4 text-left text-gray-700">Название</th>
                        <th class="border-b p-4 text-left text-gray-700">Причина отклонения</th>
                        <th class="border-b p-4 text-left text-gray-700">Дата отклонения</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $recipe)
                        @if($recipe->user_id == auth()->id()) <!-- Проверяем, принадлежит ли рецепт текущему пользователю -->
                            <tr class="hover:bg-gray-100">
                                <td class="border-b p-4">{{ $recipe->title }}</td>
                                <td class="border-b p-4">{{ $recipe->rejection_reason }}</td>
                                <td class="border-b p-4">{{ $recipe->updated_at->setTimezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

</x-app-layout>