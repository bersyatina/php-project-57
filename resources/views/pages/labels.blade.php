<x-app-layout>
    <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
        <div class="grid col-span-full">
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
                                    <form action="{{ route('labels.destroy', $label->id) }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Вы уверены?')"
                                                class="text-red-600 hover:text-red-900">
                                            Удалить
                                        </button>
                                        <a class="text-blue-600 hover:text-blue-900"
                                           href="{{ route('labels.edit', $label->id) }}">
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
