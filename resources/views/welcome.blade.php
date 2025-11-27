<x-app-layout>
    <div class="flex flex-wrap">
        <!-- Блок фильтров слева -->
        <div class="w-full md:w-1/4 p-4">
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h5 class="font-bold text-lg mb-4">Фильтры</h5>
                <form>
                    <div class="mb-4">
                        <label for="filterCategory" class="block text-sm font-medium">Категория</label>
                        <select class="border rounded w-full p-2" id="filterCategory">
                            <option>Все</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="filterTime" class="block text-sm font-medium">Время приготовления</label>
                        <select class="border rounded w-full p-2" id="filterTime">
                            <option>Все</option>
                            <option>До 30 минут</option>
                            <option>30-60 минут</option>
                            <option>Более 60 минут</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200">Применить фильтры</button>
                </form>
            </div>
            <div class="mt-4 bg-white p-4 rounded-lg shadow-lg">
                <h5 class="font-bold text-lg mb-4">Хотите добавить свой рецепт?</h5>
                <button class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-200">Добавить рецепт</button>
            </div>
        </div>
    
        <!-- Блок поиска и баннера справа -->
        <div class="w-full md:w-3/4 p-4">
            <div class="flex items-center mb-4">
                <input type="text" class="border rounded-l w-full p-2" placeholder="Поиск по рецептам">
                <button class="bg-blue-500 text-white py-2 px-4 rounded-r hover:bg-blue-600 transition duration-200">Поиск</button>
            </div>
    
            <!-- Блок категорий -->
            <h2 class="text-center text-xl font-bold mt-5">Категории</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                <div class="bg-white rounded-lg shadow-lg">
                    <img src="category1.jpg" class="rounded-t-lg" alt="Категория 1">
                    <div class="p-4">
                        <h5 class="font-bold">Выпечка</h5>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-lg">
                    <img src="category2.jpg" class="rounded-t-lg" alt="Категория 2">
                    <div class="p-4">
                        <h5 class="font-bold">Основные блюда</h5>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-lg">
                    <img src="category3.jpg" class="rounded-t-lg" alt="Категория 3">
                    <div class="p-4">
                        <h5 class="font-bold">Десерты</h5>
                    </div>
                </div>
            </div>
    
            <!-- Блок рецептов -->
            <h2 class="text-center text-xl font-bold mt-8">Одобренные рецепты</h2>
            
            </div>
        </div>
    </x-app-layout>
    