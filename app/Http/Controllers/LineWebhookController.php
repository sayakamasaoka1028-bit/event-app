<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LineWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('LINE webhook', $request->all());

        $events = $request->input('events', []);

        foreach ($events as $webhookEvent) {
            if (($webhookEvent['type'] ?? null) !== 'postback') {
                continue;
            }

            parse_str($webhookEvent['postback']['data'] ?? '', $data);

            if (($data['action'] ?? null) !== 'confirm_event' || empty($data['event_id'])) {
                continue;
            }

            $event = Event::find($data['event_id']);

            if (!$event) {
                continue;
            }

            $userId = $webhookEvent['source']['userId'] ?? null;

            $nameMap = [
                env('LINE_USER_ID_ME') => 'さやか',
                env('LINE_USER_ID_PAPA') => 'パパ',
            ];

            $actorName = $nameMap[$userId] ?? '家族';

            $event->update([
                'confirmed_by' => $actorName,
                'confirmed_at' => Carbon::now(),
            ]);

            $lineUserIds = array_filter([
                env('LINE_USER_ID_ME'),
                env('LINE_USER_ID_PAPA'),
            ]);

            Log::info('送信先LINE userIds', $lineUserIds);

            $message = "【行事確認】\n{$actorName}さんが「{$event->title}」を確認しました。";

            foreach ($lineUserIds as $lineUserId) {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.line.channel_access_token'),
                    'Content-Type' => 'application/json',
                ])->post('https://api.line.me/v2/bot/message/push', [
                    'to' => $lineUserId,
                    'messages' => [
                        [
                            'type' => 'text',
                            'text' => $message,
                        ],
                    ],
                ]);
            }

            if (!empty($webhookEvent['replyToken'])) {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.line.channel_access_token'),
                    'Content-Type' => 'application/json',
                ])->post('https://api.line.me/v2/bot/message/reply', [
                    'replyToken' => $webhookEvent['replyToken'],
                    'messages' => [
                        [
                            'type' => 'text',
                            'text' => '確認しましたを受け付けました。',
                        ],
                    ],
                ]);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
