<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'openRegistration']);
        $this->middleware('can:authorization')->except(['index', 'show', 'openRegistration']);
    }

    public function index()
    {
        $data = Tournament::with('event')->get();
        return response()->json($data);
    }

    public function openRegistration()
    {
        $data = Tournament::with('event')
        ->whereDate('close_registration', '>=', now())
        ->get();
        return response()->json($data);
    }

    public function show($id)
    {
        $data = Tournament::with('event')->find($id);
        if (!$data) {
            return response()->json(['message' => 'Tournament not found'], 404);
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'event_id' => 'required|exists:events,id',
                'game' => 'required|string|max:255',
                'is_paid' => 'required|boolean',
                "price" => 'nullable|string|max:255',
                'close_registration'  => 'required|date',
                // 'status' => 'required|in:pending,ongoing,completed'
            ]
        );

        $data = Tournament::create([
            'name' => $request->name,
            'event_id' => $request->event_id,
            'game' => $request->game,
            'is_paid' => $request->is_paid,
            'price' => $request->price,
            'close_registration' => $request->close_registration,
        ]);

        return response()->json(['message' => 'Tournament created successfully', 'tournament' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $data = Tournament::find($id);
        if (!$data) {
            return response()->json(['message' => 'Tournament not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'required|exists:events,id',
            'game' => 'required|string|max:255',
            'is_paid' => 'required|boolean',
            "price" => 'nullable|string|max:255',
            'close_registration'  => 'required|date',
        ]);

        $data->update([
            'name' => $request->name,
            'event_id' => $request->event_id,
            'game' => $request->game,
            'is_paid' => $request->is_paid,
            'price' => $request->price,
            'close_registration' => $request->close_registration,
        ]);

        return response()->json(['message' => 'Tournament updated successfully', 'data' => $data]);
    }

    public function destroy($id)
    {
        $data = Tournament::find($id);
        if (!$data) {
            return response()->json(['message' => 'Tournament not found'], 404);
        }

        $data->delete();
        return response()->json(['message' => 'Tournament deleted successfully']);
    }
}
