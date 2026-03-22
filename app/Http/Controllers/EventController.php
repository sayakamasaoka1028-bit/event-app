<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', auth()->id())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->get();

        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'nullable',
            'category' => 'nullable|string|max:255',
            'memo' => 'nullable|string',
            'notify_before_days' => 'required|integer|min:0',
        ]);

        Event::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'category' => $request->category,
            'memo' => $request->memo,
            'notify_before_days' => $request->notify_before_days,
            'is_notified' => false,
        ]);

        return redirect()->route('events.index')->with('success', '行事を登録しました');
    }

    public function show(Event $event)
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'nullable',
            'category' => 'nullable|string|max:255',
            'memo' => 'nullable|string',
            'notify_before_days' => 'required|integer|min:0',
        ]);

        $event->update([
            'title' => $request->title,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'category' => $request->category,
            'memo' => $request->memo,
            'notify_before_days' => $request->notify_before_days,
        ]);

        return redirect()->route('events.index')->with('success', '行事を更新しました');
    }

    public function destroy(Event $event)
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', '行事を削除しました');
    }
}
