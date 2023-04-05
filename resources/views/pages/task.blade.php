<x-app-layout>
    <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
        <div class="grid col-span-full">
            <h1 class="mb-5">Создать задачу</h1>
            <form method="POST"
                  action="{{ !empty($task->id) ? route('tasks.update', $task->id) : route('tasks.store') }}"
                  accept-charset="UTF-8"
                  class="w-50">
                @csrf
                @method(empty($task->id) ? 'post' : 'patch')
                <div class="flex flex-col">
                    <div>
                        <label for="name">Имя</label>
                    </div>
                    <div class="mt-2">
                        <input class="rounded border-gray-300 w-1/3"
                               name="name"
                               type="text"
                               id="name"
                               value="{{ $task->name ?? '' }}">
                    </div>
                    <div>
                        <label for="description">Описание</label>
                    </div>
                    <div class="mt-2">
                        <textarea class="rounded border-gray-300 w-1/3"
                                  name="description"
                                  id="description"
                        >{{ $task->description ?? '' }}</textarea>
                    </div>
                    <div>
                        <label for="status_id">Статус</label>
                    </div>
                    <div class="mt-2">
                        <select name="status_id" id="status_id">
                            <option value="">----------</option>
                            @if($statuses->count() > 0)
                                @foreach($statuses as $index => $status)
                                    @if($status->id === $task->status_id)
                                        <option value="{{ $status->id }}"
                                                selected="selected">{{ $status->name }}</option>
                                    @else
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="assigned_to_id">Исполнитель</label>
                    </div>
                    <div class="mt-2">
                        <select name="assigned_to_id" id="assigned_to_id">
                            <option value="">----------</option>
                            @if($users->count() > 0)
                                @foreach($users as $index => $user)
                                    @if($user->id === $task->assigned_to_id)
                                        <option value="{{ $user->id }}" selected="selected">{{ $user->name }}</option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="labels">Метки</label>
                    </div>
                    <div>
                        <select multiple="multiple" name="labels[]" class="rounded border-gray-300 w-1/3 h-32"
                                id="labels">
                            <option value=""></option>
                            @isset($labels[0])
                                @foreach($labels as $index => $label)
                                    @if(in_array($label->id, $taskLabels))
                                        <option value="{{ $label->id }}" selected="selected">{{ $label->name }}</option>
                                    @else
                                        <option value="{{ $label->id }}">{{ $label->name }}</option>
                                    @endif
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="mt-2">
                        <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                               type="submit" value="{{ empty($task->id) ? 'Создать' : 'Обновить'  }}">
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
