@php
    function displayStars($rating)
    {
        $fullStar = '★';
        $emptyStar = '☆';
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $rating ? $fullStar : $emptyStar;
        }
        return $stars;
    }
@endphp

<x-app-layout>
    <div class="container mx-auto py-12">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <div class="mb-6 text-center">
                <h1 class="text-4xl font-bold mb-4">{{ $recipe->title }}</h1>
                <p class="text-gray-600 mb-4">Автор: {{ $recipe->user->username }}</p>
                @if ($recipe->image_url)
                    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}"
                        class="w-full h-80 object-cover rounded-lg mb-6">
                @endif
            </div>

            <div class="mb-8 border-b pb-6">
                <h2 class="text-2xl font-semibold mb-2">Описание</h2>
                <p class="text-gray-700 break-words whitespace-pre-line">{{ $recipe->description }}</p>
            </div>

            <div class="mb-8 border-b pb-6">
                <h2 class="text-2xl font-semibold mb-2">Ингредиенты</h2>
                <ul class="list-disc list-inside space-y-1 text-gray-700" id="ingredientList">
                    @foreach($recipe->ingredients as $ingredient)
                        <li data-original-quantity="{{ $ingredient->quantity }}" data-unit="{{ $ingredient->unit }}">
                            <span class="ingredient-name">{{ $ingredient->name }}</span>: <span class="ingredient-quantity">{{ $ingredient->quantity }}</span> <span class="ingredient-unit">{{ $ingredient->unit }}</span>
                        </li>
                    @endforeach
                </ul>
                @if($recipe->steps->isNotEmpty())
                    <h3 class="text-xl font-semibold mt-6 mb-4 text-gray-800">Шаги приготовления</h3>
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold mb-4">Шаги приготовления</h2>
                        <div class="steps-slider">
                            @foreach($recipe->steps as $step)
                            <div class="step-slide mb-8">
                                <div class="flex flex-col md:flex-row gap-6">
                                    <div class="md:w-1/2">
                                        <h3 class="text-xl font-semibold mb-2">Шаг {{ $step->step_number }}</h3>
                                        <p class="text-gray-700">{{ $step->description }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-gray-600">Шаги приготовления пока не добавлены.</p>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                <div class="flex items-center space-x-2">
                    <!-- Иконка категории -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold">Категория</h3>
                        <p class="text-gray-700">{{ $recipe->category->name ?? 'Категория не указана' }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <!-- Иконка времени приготовления -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold">Время приготовления</h3>
                        <p class="text-gray-700">{{ $recipe->cooking_time }} минут</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <!-- Иконка сложности -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 16.6l-3.1 1.65 2.05-3.5-3.05-2.65 4.1-.3 1.75-3.8 1.75 3.8 4.1.3-3.05 2.65 2.05 3.5-3.1-1.65" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold">Сложность</h3>
                        <p class="text-gray-700">{{ $recipe->difficulty == 'easy' ? 'Легко' : ($recipe->difficulty == 'medium' ? 'Средне' : 'Сложно') }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <!-- Иконка порций -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6h-6v6h6V6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h6v6H6v-6z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold">Количество порций</h3>
                        <input type="number" id="servingsInput" value="{{ $recipe->servings }}" min="1"
                            class="text-gray-700 border rounded-md px-2 py-1 w-20 text-center">
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 16a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold">Калорийность</h3>
                            <p class="text-gray-700">{{ $recipe->calories }} ккал</p>
                        </div>
                    </div>
                </div>

                {{-- New BJU section --}}
                <div class="flex items-center space-x-2">
                    <div>
                        <h3 class="text-lg font-semibold">Белки</h3>
                        <p class="text-gray-700">{{ $recipe->proteins ?? 0 }}г</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <div>
                        <h3 class="text-lg font-semibold">Жиры</h3>
                        <p class="text-gray-700">{{ $recipe->fats ?? 0 }}г</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <div>
                        <h3 class="text-lg font-semibold">Углеводы</h3>
                        <p class="text-gray-700">{{ $recipe->carbs ?? 0 }}г</p>
                    </div>
                </div>
            </div>

            @auth
                <form action="{{ route('recipes.addToFavorites', $recipe->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition duration-200 text-center font-semibold text-lg mb-4">
                        Добавить в избранное
                    </button>
                </form>
            @endauth

            <a href="{{ route('home.index', $recipe->id) }}"
                class="block w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200 text-center font-semibold text-lg">
                Вернуться назад
            </a>
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            <!-- Комментарии -->
            <div class="mt-8">
                <h3 class="text-2xl font-bold mb-4">Комментарии</h3>
                @auth
                    <form action="{{ route('comments.store', $recipe->id) }}" method="POST" class="mb-6">
                        @csrf
                        <textarea name="comment" class="border w-full p-4 rounded-lg" placeholder="Ваш комментарий"
                            rows="3"></textarea>
                        <label for="rating" class="block mt-2 mb-1 text-gray-700">Рейтинг:</label>
                        <select name="rating" class="border p-2 rounded w-full">
                            <option value="">Выберите рейтинг</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <button type="submit"
                            class="mt-4 bg-green-500 text-white py-2 px-6 rounded-lg font-semibold hover:bg-green-600 transition duration-200">Добавить
                            комментарий</button>
                    </form>
                @else
                    <p>Пожалуйста, <a href="{{ route('login') }}" class="text-blue-500 underline">войдите</a>, чтобы
                        оставить комментарий.</p>
                @endauth

                @if ($recipe->comments->isEmpty())
                    <p>Пока нет комментариев.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($recipe->comments as $comment)
                            <div class="p-4 bg-gray-100 rounded-lg shadow">
                                <p class="font-semibold">{{ $comment->user->username }}</p>
                                <p class="text-yellow-500">{{ displayStars($comment->rating) }}</p>
                                <p class="mt-2 text-gray-700">{{ $comment->comment }}</p>

                                @auth
                                    @if (auth()->user()->role === 'admin' || auth()->id() === $comment->user_id)
                                        <button class="text-blue-500 underline edit-comment-btn" data-comment-id="{{ $comment->id }}"
                                            data-comment-rating="{{ $comment->rating }}">
                                            Редактировать
                                        </button>
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 underline ml-2">Удалить</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    </div>


                    <!-- Модальное окно для редактирования комментария -->
                    <div id="editCommentModal"
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                        <div class="bg-white p-6 rounded-lg w-96">
                            <h2 class="text-xl font-semibold mb-4">Редактировать комментарий</h2>
                            <form id="editCommentForm" action="" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="editCommentText" class="block text-sm font-medium">Комментарий</label>
                                    <textarea name="comment" id="editCommentText"
                                        class="border border-gray-300 rounded w-full p-2" rows="3" required></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="editCommentRating" class="block text-sm font-medium">Рейтинг</label>
                                    <input type="number" name="rating" id="editCommentRating"
                                        class="border border-gray-300 rounded w-full p-2" min="1" max="5" required>
                                </div>
                                <button type="submit"
                                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Сохранить
                                    изменения</button>
                                <button type="button" onclick="closeEditModal()"
                                    class="bg-gray-300 text-gray-800 py-2 px-4 rounded hover:bg-gray-400">Отменить</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Добавляем Swiper.js для слайдера -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.steps-slider', {
            direction: 'vertical',
            slidesPerView: 1,
            spaceBetween: 30,
            mousewheel: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const editCommentButtons = document.querySelectorAll('.edit-comment-btn');
        const editCommentModal = document.getElementById('editCommentModal');
        const editCommentForm = document.getElementById('editCommentForm');
        const editCommentText = document.getElementById('editCommentText');
        const editCommentRating = document.getElementById('editCommentRating');

        editCommentButtons.forEach(button => {
            button.addEventListener('click', function () {
                const commentId = this.getAttribute('data-comment-id');
                const commentText = this.parentElement.querySelector('.text-gray-700').innerText;
                const commentRating = this.getAttribute('data-comment-rating');

                // Заполняем форму данными комментария
                editCommentText.value = commentText;
                editCommentRating.value = commentRating;
                editCommentForm.action = `/comments/${commentId}`;

                // Отображаем модальное окно
                editCommentModal.classList.remove('hidden');
            });
        });

        // Функция для закрытия модального окна
        window.closeEditModal = function () {
            editCommentModal.classList.add('hidden');
        };
    });
    </script>

    <script>
        // Initial servings from the recipe
        const originalServings = {{ $recipe->servings }};

        document.getElementById('servingsInput').addEventListener('input', function(e) {
            const desiredServings = parseInt(e.target.value);
            if (isNaN(desiredServings) || desiredServings <= 0) {
                // Optionally revert to original servings or display a message
                // For now, let's just not update if input is invalid
                return;
            }

            const ingredientItems = document.querySelectorAll('#ingredientList li');

            ingredientItems.forEach(item => {
                const originalQuantity = parseFloat(item.getAttribute('data-original-quantity'));
                const unit = item.getAttribute('data-unit');

                if (!isNaN(originalQuantity)) {
                    const newQuantity = (originalQuantity / originalServings) * desiredServings;
                    // Update the displayed quantity, maybe format it to a reasonable number of decimal places
                    item.querySelector('.ingredient-quantity').textContent = newQuantity.toFixed(1); // Example: one decimal place
                }
            });
        });
    </script>
</x-app-layout>