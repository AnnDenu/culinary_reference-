<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">История просмотров</h2>

        @if($history->isEmpty())
            <div class="text-gray-600 text-lg">
                <p>Вы еще не просматривали рецепты.</p>
            </div>
        @else
            <ul class="space-y-4">
                @foreach($history as $view)
                    <li class="flex items-center justify-between p-4 bg-gray-50 rounded-lg shadow-sm hover:bg-gray-100 transition">
                        <a href="{{ route('recipes.trackView', $view->recipe->id) }}" class="text-lg text-blue-600 font-medium hover:underline">
                            {{ $view->recipe->title }}
                        </a>
                        <span class="text-sm text-gray-400">{{ $view->updated_at->diffForHumans() }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <!-- Иконка очистки истории -->
        <div class="mt-8 flex justify-end">
            <form action="{{ route('recipes.clearHistory') }}" method="POST" class="flex items-center">
                @csrf
                <button type="submit" class="text-red-600 hover:text-red-800 focus:outline-none flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m-5 4v6m4-6v6m-8-6v6m-2 4h12a2 2 0 002-2V6H5v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Очистить историю</span>
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
