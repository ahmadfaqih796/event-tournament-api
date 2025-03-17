<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $keyword = $request->input('search');
        $user = Team::with(['createdBy'])->where('name', 'like', "%{$keyword}%")->get();
        return response()->json($user);
    }

    public function show($id)
    {
        $data = Team::find($id);
        if (!$data) {
            return response()->json(['message' => 'Team not found'], 404);
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = Team::create([
            'name' => $request->name,
            'game' => $request->game,
            'leader_team' => $request->leader_team,
            'member_team' => $request->member_team,
            'created_by' => Auth::id()
        ]);

        return response()->json(['message' => 'Team created successfully', 'data' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $data = Team::find($id);
        if (!$data) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data->update([
            'name' => $request->name,
            'game' => $request->game,
            'leader_team' => $request->leader_team,
            'member_team' => $request->member_team,
            'created_by' => Auth::id()
        ]);

        return response()->json(['message' => 'Team updated successfully', 'data' => $data]);
    }

    public function destroy($id)
    {
        $data = Team::find($id);
        if (!$data) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $data->delete();
        return response()->json(['message' => 'Team deleted successfully']);
    }
}
