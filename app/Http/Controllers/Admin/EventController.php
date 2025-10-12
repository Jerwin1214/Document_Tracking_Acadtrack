<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    // GET /admin/events
    public function index()
    {
        // FullCalendar needs JSON with id, title, start, end
        $events = Event::select('id', 'title', 'start', 'end')->get();
        return response()->json($events);
    }

    // POST /admin/events
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end'   => 'required|date|after_or_equal:start',
        ]);

        $event = Event::create([
            'title' => $request->title,
            'start' => $request->start,
            'end'   => $request->end,
        ]);

        return response()->json($event);
    }

    // PUT /admin/events/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end'   => 'required|date|after_or_equal:start',
        ]);

        $event = Event::findOrFail($id);
        $event->update([
            'title' => $request->title,
            'start' => $request->start,
            'end'   => $request->end,
        ]);

        return response()->json($event);
    }

    // DELETE /admin/events/{id}
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['success' => true]);
    }
}
