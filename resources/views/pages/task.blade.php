<x-app-layout>
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
                       value="{{ old('name') ?? $task->name }}">
            </div>
            @error('name')
                <div class="text-rose-600">{{ $message }}</div>
            @enderror
            <div>
                <label for="description">Описание</label>
            </div>
            <div class="mt-2">
                        <textarea class="rounded border-gray-300 w-1/3"
                                  name="description"
                                  id="description"
                        >{{ old('description') ?? $task->description }}</textarea>
            </div>
            @error('description')
            <div class="text-rose-600">{{ $message }}</div>
            @enderror
            <div>
                <label for="status_id">Статус</label>
            </div>
            <div class="mt-2">
                <select class="rounded border-gray-300 w-1/3" name="status_id" id="status_id">
                    <option value="">----------</option>
                    @if($statuses->count() > 0)
                        @foreach($statuses as $index => $status)
                            @if($status->id == old('status_id') || $status->id === $task->status_id)
                                <option value="{{ $status->id }}"
                                        selected="selected">{{ $status->name }}</option>
                            @else
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
            @error('status_id')
            <div class="text-rose-600">{{ $message }}</div>
            @enderror
            <div>
                <label for="assigned_to_id">Исполнитель</label>
            </div>
            <div class="mt-2">
                <select class="rounded border-gray-300 w-1/3" name="assigned_to_id" id="assigned_to_id">
                    <option value="">----------</option>
                    @if($users->count() > 0)
                        @foreach($users as $index => $user)
                            @if($user->id == old('assigned_to_id') || $user->id === $task->assigned_to_id)
                                <option value="{{ $user->id }}" selected="selected">{{ $user->name }}</option>
                            @else
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
            @error('assigned_to_id')
            <div class="text-rose-600">{{ $message }}</div>
            @enderror
            <div class="mt-2">
                <label for="labels">Метки</label>
            </div>
            <div>
                <select multiple="multiple" name="labels[]" class="rounded border-gray-300 w-1/3 h-32"
                        id="labels">
                    <option value=""></option>
                    @isset($labels[0])
                        @foreach($labels as $index => $label)
                            @if(!empty(old('labels')) && in_array($label->id, old('labels')))
                                <option value="{{ $label->id }}" selected>{{ $label->name }}</option>
                            @elseif(empty(old('labels')) && in_array($label->id, $taskLabels))
                                <option value="{{ $label->id }}" selected>{{ $label->name }}</option>
                            @else
                                <option value="{{ $label->id }}">{{ $label->name }}</option>
                            @endif
                        @endforeach
                    @endisset
                </select>
            </div>
            @error('labels')
            <div class="text-rose-600">{{ $message }}</div>
            @enderror
            <div class="mt-2">
                <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                       type="submit" value="{{ empty($task->id) ? 'Создать' : 'Обновить'  }}">
            </div>
        </div>
    </form>
</x-app-layout>
