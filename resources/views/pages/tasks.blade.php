<x-app-layout>
    <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
        <div class="grid col-span-full">
            <h1 class="mb-5">Задачи</h1>

            <div class="w-full flex items-center">
                <div>
                    <form method="GET" action="{{ route('tasks.index')}}" accept-charset="UTF-8"
                          class="">
                        <div class="flex">
                            <div>
                                <select class="rounded border-gray-300" name="filter[status_id]">
                                    <option selected="selected" value="">Статус</option>
                                    @foreach($statuses as $index => $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select class="ml-2 rounded border-gray-300" name="filter[created_by_id]">
                                    <option selected="selected" value="">Автор</option>
                                    @foreach($users as $index => $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select class="ml-2 rounded border-gray-300" name="filter[assigned_to_id]">
                                    <option selected="selected" value="">Исполнитель</option>
                                    @foreach($users as $index => $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                                       type="submit" value="Применить">
                            </div>
                        </div>
                    </form>
                </div>
                @auth()
                    <div class="ml-auto">
                        <a href="{{ route('tasks.create') }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            Создать задачу
                        </a>
                    </div>
                @endauth
            </div>

            <table class="mt-4">
                <thead class="border-b-2 border-solid border-black text-left">
                <tr>
                    <th>ID</th>
                    <th>Статус</th>
                    <th>Имя</th>
                    <th>Автор</th>
                    <th>Исполнитель</th>
                    <th>Дата создания</th>
                    @auth()
                        <th>Действия</th>
                    @endauth
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $index => $task)
                    <tr class="border-b border-dashed text-left">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $statuses->find($task->status_id)->name }}</td>
                        <td>
                            <a class="text-blue-600 hover:text-blue-900"
                               href="{{ route('tasks.show', $task->id) }}">
                                {{ $task->name }}
                            </a>
                        </td>
                        <td>{{ $users->find($task->created_by_id)->name }}</td>
                        <td>{{ $users->find($task->assigned_to_id)->name ?? '' }}</td>
                        <td>{{ date('d.m.Y', strtotime($task->created_at)) }}</td>
                        @auth()
                            <td>
                                <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-600 hover:text-blue-900">
                                    Изменить
                                </a>
                            </td>
                        @endauth
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
