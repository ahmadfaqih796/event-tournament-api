<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware('can:authorization')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $keyword = $request->input('search');

        $events = Event::with(['createdBy', 'tournaments'])
            ->where('name', 'like', "%{$keyword}%")
            ->orWhereHas('createdBy', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->orWhereHas('tournaments', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('game', 'like', "%{$keyword}%");
            })
            ->get();

        return response()->json($events);
    }

    public function show($id)
    {
        $event = Event::with('createdBy')->find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json($event);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255'
        ]);

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'created_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Event created successfully', 'event' => $event], 201);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        if ($event->is_approved === 1) {
            return response()->json(['message' => 'Event already approved'], 400);
        }
        if ($event->is_approved === 0) {
            return response()->json(['message' => 'Event already rejected'], 400);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255'
        ]);

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'is_approved' => $request->is_approved
        ]);

        return response()->json(['message' => 'Event updated successfully', 'event' => $event]);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }
}
