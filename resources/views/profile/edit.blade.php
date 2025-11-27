<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Профиль') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-lg rounded-2xl">
                <div class="max-w-xl mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        Профиль пользователя
                    </h2>

                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('patch')

                        <!-- Аватар -->
                        <div class="flex flex-col items-center space-y-4">
                            <div class="relative group">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" 
                                         alt="Avatar" 
                                         class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg group-hover:opacity-75 transition-opacity">
                                @else
                                    <div class="h-32 w-32 rounded-full bg-red-100 flex items-center justify-center border-4 border-white shadow-lg group-hover:bg-red-200 transition-colors">
                                        <span class="text-red-600 font-bold text-4xl">
                                            {{ substr(auth()->user()->username ?? auth()->user()->email, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                
                                <label for="avatar" class="absolute inset-0 flex items-center justify-center rounded-full cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-full text-sm font-medium">
                                        Изменить фото
                                    </span>
                                </label>
                                <input type="file" 
                                       id="avatar" 
                                       name="avatar" 
                                       accept="image/*"
                                       class="hidden"
                                       onchange="previewImage(event)">
                            </div>
                            @error('avatar')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Имя пользователя -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">
                                Имя пользователя
                            </label>
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   value="{{ old('username', auth()->user()->username) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            @error('username')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', auth()->user()->email) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Кнопки -->
                        <div class="flex items-center justify-end space-x-4">
                            <button type="reset" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Отменить
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Сохранить
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Изменение пароля -->
            <div class="p-4 sm:p-8 bg-white shadow-lg rounded-2xl">
                <div class="max-w-xl mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        Изменение пароля
                    </h2>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <!-- Текущий пароль -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">
                                Текущий пароль
                            </label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Новый пароль -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Новый пароль
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Подтверждение пароля -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Подтверждение пароля
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>

                        <!-- Кнопки -->
                        <div class="flex items-center justify-end space-x-4">
                            <button type="reset" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Отменить
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Изменить пароль
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const container = input.closest('.relative');
                    const currentImage = container.querySelector('img');
                    const currentInitial = container.querySelector('div');
                    
                    if (currentImage) {
                        currentImage.src = e.target.result;
                    } else if (currentInitial) {
                        const newImage = document.createElement('img');
                        newImage.src = e.target.result;
                        newImage.alt = 'Avatar';
                        newImage.className = 'h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg group-hover:opacity-75 transition-opacity';
                        currentInitial.replaceWith(newImage);
                    }
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
