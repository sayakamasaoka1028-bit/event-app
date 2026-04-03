<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendTomorrowEvents extends Command
{
    protected $signature = 'app:send-tomorrow-events {date?}';
    protected $description = '明日の行事をLINEに送信する';

    public function handle()
    {

$date = $this->argument('date') ?? now()->addDay()->toDateString();

$events = Event::whereDate('event_date', $date)
    ->where('is_notified', false)
    ->orderBy('event_time', 'asc')
    ->get();

if ($events->isEmpty()) {
    $this->info("{$date} の行事はありません");
    return Command::SUCCESS;
}
        $lineUserIds = array_filter([
            env('LINE_USER_ID_ME'),
            env('LINE_USER_ID_PAPA'),
        ]);

        foreach ($events as $event) {
            foreach ($lineUserIds as $lineUserId) {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.line.channel_access_token'),
                    'Content-Type' => 'application/json',
                ])->post('https://api.line.me/v2/bot/message/push', [
                    'to' => $lineUserId,
                    'messages' => [
                        [
                            'type' => 'template',
                            'altText' => '行事確認',
                            'template' => [
                                'type' => 'confirm',
                                'text' => "【明日の行事】\n{$event->title}\n日付：{$event->event_date}" . ($event->event_time ? "\n時間：{$event->event_time}" : ''),
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

            $event->update([
                'is_notified' => true,
            ]);
        }

$this->info("{$date} の行事通知を送信しました");
        return Command::SUCCESS;
    }
}
