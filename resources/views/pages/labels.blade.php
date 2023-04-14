<x-app-layout>
    <h1 class="mb-5">Метки</h1>
    @auth()
        <div>
            <a href="{{ route('labels.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Создать метку </a>
        </div>
    @endauth
    <table class="mt-4">
        <thead class="border-b-2 border-solid border-black text-left">
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Описание</th>
            <th>Дата создания</th>
            @auth()
                <th>Действия</th>
            @endauth
        </tr>
        </thead>
        <tbody>
        @if(!empty($labels))
            @foreach($labels as $index => $label)
                <tr class="border-b border-dashed text-left">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $label->name }}</td>
                    <td>{{ $label->description }}</td>
                    <td>{{ date('d.m.Y', strtotime($label->created_at)) }}</td>
                    @auth()
                        <td>
                            <a class="text-red-600 hover:text-red-900"
                               rel="nofollow" data-method="delete"
                               data-confirm="Вы уверены?"
                               href="{{ route('labels.destroy', $label->id) }}">
                                Удалить
                            </a>
                            <a href="{{ route('labels.edit', $label->id) }}" class="text-blue-600 hover:text-blue-900">
                                Изменить
                            </a>
                        </td>
                    @endauth
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</x-app-layout>
