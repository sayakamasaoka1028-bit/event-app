<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">行事一覧</h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('events.create') }}"
               class="inline-block bg-blue-500 text-white px-4 py-2 rounded">
                ＋追加
            </a>
        </div>

        @forelse($events as $event)
            <div class="bg-white shadow rounded-lg p-5 mb-4 border">
                <div class="mb-2 text-lg font-bold text-gray-800">
                    {{ $event->title }}
                </div>

                <div class="text-gray-700 mb-1">
                    日付：{{ $event->event_date }}
                </div>

                @if($event->event_time)
                    <div class="text-gray-700 mb-1">
                        時間：{{ $event->event_time }}
                    </div>
                @endif

                @if($event->category)
                    <div class="text-gray-700 mb-1">
                        カテゴリ：{{ $event->category }}
                    </div>
                @endif

                @if($event->memo)
                    <div class="text-gray-700 mb-3 whitespace-pre-line">
                        メモ：{{ $event->memo }}
                    </div>
                @endif

                <div class="flex gap-2 mt-4">
                    <a href="{{ route('events.edit', $event->id) }}"
                       class="bg-yellow-500 text-white px-4 py-2 rounded">
                        編集
                    </a>

                    <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                          onsubmit="return confirm('削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 text-white px-4 py-2 rounded">
                            削除
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-gray-500">行事がまだ登録されていません。</div>
        @endforelse
    </div>
</x-app-layout>
