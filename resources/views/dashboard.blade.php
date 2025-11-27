<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Мои рецепты') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">

                <h3 class="text-lg font-semibold mb-4">Ваши рецепты:</h3>

                @if(isset($recipes) && $recipes->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($recipes as $recipe)
                            <div class="border rounded-lg overflow-hidden shadow">
                                <a href="{{ route('recipes.show', $recipe->id) }}">
                                    @if($recipe->image_url)
                                        <img src="{{ asset('storage/' . $recipe->image_url) }}" alt="{{ $recipe->title }}" class="w-full h-48 object-cover">
                                    @else
                                        <img src="{{ asset('images/default_recipe_image.png') }}" alt="Без изображения" class="w-full h-48 object-cover">
                                    @endif
                                    <div class="p-4">
                                        <h4 class="font-bold text-md mb-2">{{ $recipe->title }}</h4>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>У вас пока нет добавленных рецептов.</p>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
