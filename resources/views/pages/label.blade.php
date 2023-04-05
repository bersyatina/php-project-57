<x-app-layout>
    <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
        <div class="grid col-span-full">
            <h1 class="mb-5">Создать метку</h1>
            <form method="POST"
                  action="{{ !empty($label->id) ? route('labels.update', $label->id) : route('labels.store') }}"
                  accept-charset="UTF-8"
                  class="w-50">
                @csrf
                @method(empty($label->id) ? 'post' : 'patch')
                <div class="flex flex-col">
                    <div>
                        <label for="name">Имя</label>
                    </div>
                    <div class="mt-2">
                        <input class="rounded border-gray-300 w-1/3"
                               name="name"
                               type="text"
                               id="name"
                               value="{{ $label->name ?? '' }}">
                    </div>
                    <div>
                        <label for="description">Описание</label>
                    </div>
                    <div class="mt-2">
                        <textarea class="rounded border-gray-300 w-1/3"
                               name="description"
                               id="description"
                        >{{ $label->description ?? '' }}</textarea>
                    </div>
                    <div class="mt-2">
                        <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                               type="submit" value="{{ empty($label->id) ? 'Создать' : 'Обновить'  }}">
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
