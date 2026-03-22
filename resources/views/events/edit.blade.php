<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">行事編集</h2>
    </x-slot>

    <div class="p-6 max-w-2xl mx-auto">
        <form action="{{ route('events.update', $event->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="font-semibold">タイトル</label>
                <input type="text" name="title" value="{{ $event->title }}" class="col-span-2 border rounded p-2 w-full">
            </div>

            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="font-semibold">日付</label>
                <input type="date" name="event_date" value="{{ $event->event_date }}" class="col-span-2 border rounded p-2 w-full">
            </div>

            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="font-semibold">時間</label>
                <input type="time" name="event_time" value="{{ $event->event_time }}" class="col-span-2 border rounded p-2 w-full">
            </div>

            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="font-semibold">カテゴリ</label>
                <input type="text" name="category" value="{{ $event->category }}" class="col-span-2 border rounded p-2 w-full">
            </div>

            <div class="grid grid-cols-3 gap-4 items-start">
                <label class="font-semibold">メモ</label>
                <textarea name="memo" class="col-span-2 border rounded p-2 w-full">{{ $event->memo }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="font-semibold">何日前に通知</label>
                <input type="number" name="notify_before_days" value="{{ $event->notify_before_days }}" class="col-span-2 border rounded p-2 w-full">
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('events.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">
                    戻る
                </a>

                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded shadow">
                    更新
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
