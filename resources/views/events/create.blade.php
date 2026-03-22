<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">行事登録</h2>
    </x-slot>

    <div class="p-6 max-w-2xl mx-auto">
        <form action="{{ route('events.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="font-semibold">タイトル</label>
                <input type="text" name="title" class="col-span-2 border rounded p-2 w-full">
            </div>

            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="font-semibold">日付</label>
                <input type="date" name="event_date" class="col-span-2 border rounded p-2 w-full">
            </div>

            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="font-semibold">時間</label>
                <input type="time" name="event_time" class="col-span-2 border rounded p-2 w-full">
            </div>

            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="font-semibold">カテゴリ</label>
                <input type="text" name="category" class="col-span-2 border rounded p-2 w-full">
            </div>

            <div class="grid grid-cols-3 gap-4 items-start">
                <label class="font-semibold">メモ</label>
                <textarea name="memo" class="col-span-2 border rounded p-2 w-full"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="font-semibold">何日前に通知</label>
                <input type="number" name="notify_before_days" value="1" class="col-span-2 border rounded p-2 w-full">
            </div>

            <div class="text-right">
                <button type="submit" style="background:#16a34a; color:white; padding:10px 24px; border-radius:8px;">
                    登録
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
