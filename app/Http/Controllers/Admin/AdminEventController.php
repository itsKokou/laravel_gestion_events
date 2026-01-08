<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertEventRequest;
use App\Models\Event;
use Illuminate\Support\Str;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::query()->orderByDesc('starts_at')->paginate(20);

        return view('admin.events.index', [
            'events' => $events,
        ]);
    }

    public function create()
    {
        return view('admin.events.form', [
            'event' => new Event(),
        ]);
    }

    public function store(UpsertEventRequest $request)
    {
        $data = $request->validated();

        $event = Event::create([
            ...$data,
            'slug' => Str::slug($data['slug']),
            'published_at' => $data['status'] === 'published' ? now() : null,
            'archived_at' => $data['status'] === 'archived' ? now() : null,
        ]);

        return redirect()->route('admin.events.edit', $event)->with('status', 'Soirée créée.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.form', [
            'event' => $event,
        ]);
    }

    public function update(UpsertEventRequest $request, Event $event)
    {
        $data = $request->validated();

        $event->fill($data);
        $event->slug = Str::slug($data['slug']);

        if ($data['status'] === 'published' && $event->published_at === null) {
            $event->published_at = now();
        }
        if ($data['status'] !== 'published') {
            $event->published_at = null;
        }
        if ($data['status'] === 'archived' && $event->archived_at === null) {
            $event->archived_at = now();
        }
        if ($data['status'] !== 'archived') {
            $event->archived_at = null;
        }

        $event->save();

        return redirect()->route('admin.events.edit', $event)->with('status', 'Soirée mise à jour.');
    }
}
