<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LineWebhookController;
use Illuminate\Support\Facades\Http;
use App\Models\Event;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// LINE Webhook は auth の外
Route::post('/line/webhook', [LineWebhookController::class, 'handle']);

// LINEテスト送信
Route::get('/line/test', function () {
    $userId = env('LINE_USER_ID_ME');

    Http::withHeaders([
        'Authorization' => 'Bearer ' . config('services.line.channel_access_token'),
        'Content-Type' => 'application/json',
    ])->post('https://api.line.me/v2/bot/message/push', [
        'to' => $userId,
        'messages' => [
            [
                'type' => 'text',
                'text' => '行事アプリからテスト送信成功！',
            ],
        ],
    ]);

    return '送信したよ';
});

// パパだけにテスト送信
Route::get('/line/test-papa', function () {
    $userId = env('LINE_USER_ID_PAPA');

    \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . config('services.line.channel_access_token'),
        'Content-Type' => 'application/json',
    ])->post('https://api.line.me/v2/bot/message/push', [
        'to' => $userId,
        'messages' => [
            [
                'type' => 'text',
                'text' => 'パパ宛テスト送信',
            ],
        ],
    ]);

    return 'パパに送信したよ';
});

// 行事確認ボタン付きテスト送信（自分＋パパ）
Route::get('/line/event-test/{event}', function (Event $event) {
    $lineUserIds = array_filter([
        env('LINE_USER_ID_ME'),
        env('LINE_USER_ID_PAPA'),
    ]);

    foreach ($lineUserIds as $userId) {
        Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.line.channel_access_token'),
            'Content-Type' => 'application/json',
        ])->post('https://api.line.me/v2/bot/message/push', [
            'to' => $userId,
            'messages' => [
                [
                    'type' => 'template',
                    'altText' => '行事確認',
                    'template' => [
                        'type' => 'confirm',
                        'text' => "【行事確認】\n{$event->title}\n日付：{$event->event_date}",
                        'actions' => [
                            [
                                'type' => 'postback',
                                'label' => '確認しました',
                                'data' => 'action=confirm_event&event_id=' . $event->id,
                                'displayText' => '確認しました',
                            ],
                            [
                                'type' => 'postback',
                                'label' => 'あとで見る',
                                'data' => 'action=later_event&event_id=' . $event->id,
                                'displayText' => 'あとで見る',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    return '2人に送信したよ';
});
Route::middleware('auth')->group(function () {
    Route::resource('events', EventController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
