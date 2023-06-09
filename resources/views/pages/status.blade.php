<x-app-layout>
    <h1 class="mb-5">Создать статус</h1>
    <form method="POST"
          action="{{ !empty($status->id) ? route('task_statuses.update', $status->id) : route('task_statuses.store') }}"
          accept-charset="UTF-8" class="w-50">
        @csrf
        @method(empty($status->id) ? 'post' : 'patch')
        <div class="flex flex-col">
            <div>
                <label for="name">Имя</label>
            </div>
            <div class="mt-2">
                <input class="rounded border-gray-300 w-1/3" name="name" type="text" id="name"
                       value="{{ $status->name ?? '' }}">
            </div>
            @error('name')
                <div class="text-rose-600">{{ $message }}</div>
            @enderror
            <div class="mt-2">
                <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit"
                       value="{{ empty($status->id) ? 'Создать' : 'Обновить'  }}">
            </div>
        </div>
    </form>
</x-app-layout>
