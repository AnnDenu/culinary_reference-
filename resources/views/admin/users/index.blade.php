<x-app-layout>
    <div class="bg-gradient-to-r from-yellow-100 via-orange-100 to-yellow-200">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-2xl font-semibold mb-6">Управление пользователями</h1>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-200">Имя</th>
                    <th class="py-2 px-4 bg-gray-200">Email</th>
                    <th class="py-2 px-4 bg-gray-200">Роль</th>
                    <th class="py-2 px-4 bg-gray-200">Статус</th>
                    <th class="py-2 px-4 bg-gray-200">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="py-2 px-4">{{ $user->username }}</td>
                        <td class="py-2 px-4">{{ $user->email }}</td>
                        <td class="py-2 px-4">
                            @php
                                $roleTranslations = [
                                    'admin' => 'Администратор',
                                    'user' => 'Пользователь'
                                ];
                            @endphp
                            {{ $roleTranslations[$user->role] ?? $user->role }}
                        </td>
                        <td class="py-2 px-4">
                            @if ($user->is_banned)
                                <span class="text-red-500">Забанен</span>
                            @else
                                <span class="text-green-500">Активен</span>
                            @endif
                        </td>
                        <td class="py-2 px-4">
                            <!-- Форма изменения роли -->
                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST"
                                  class="inline-block">
                                @csrf
                                <select name="role" onchange="this.form.submit()" class="border rounded py-1 px-2">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Пользователь</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Администратор</option>
                                </select>
                            </form>

                            <!-- Форма бана/разбана -->
                            <form action="{{ route('admin.users.ban', $user) }}" method="POST"
                                  class="inline-block ml-4">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600">
                                    @if ($user->is_banned)
                                        Разбанить
                                    @else
                                        Забанить
                                    @endif
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Пагинация -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
