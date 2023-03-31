<x-app-layout>
    <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
        <div class="grid col-span-full">
            <h1 class="mb-5">Статусы</h1>
            @auth()
            <div>
                <a href="{{ route('task_statuses.create') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Создать статус </a>
            </div>
            @endauth

            <table class="mt-4">
                <thead class="border-b-2 border-solid border-black text-left">
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Дата создания</th>
                    @auth()
                    <th>Действия</th>
                    @endauth
                </tr>
                </thead>
                <tbody>
                @if(!empty($statuses))
                    @foreach($statuses as $index => $status)
                    <tr class="border-b border-dashed text-left">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $status->name }}</td>
                        <td>{{ date('d.m.Y', strtotime($status->created_at)) }}</td>
                        @auth()
                        <td>
                            <form action="{{ route('task_statuses.destroy', $status->id) }}" method="post">
                                @method('delete')
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Вы уверены?')"
                                        class="text-red-600 hover:text-red-900">
                                    Удалить
                                </button>
                                <a class="text-blue-600 hover:text-blue-900"
                                   href="{{ route('task_statuses.edit', $status->id) }}">
                                    Изменить </a>
                            </form>
                        </td>
                        @endauth
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

        </div>
    </div>
</x-app-layout>
