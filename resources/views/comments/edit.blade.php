<x-app-layout>
    <div class="container">
        <h1>Редактировать комментарий</h1>
        <form action="{{ route('comments.update', $comment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <textarea name="content" class="border w-full p-2 rounded">{{ $comment->content }}</textarea>
            <button type="submit" class="mt-2 bg-blue-500 text-white py-1 px-4 rounded">Обновить комментарий</button>
        </form>
    </div>
</x-app-layout>