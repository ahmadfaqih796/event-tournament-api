<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware('can:authorization')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $keyword = $request->input('search');
        $user = Game::get();
        return response()->json($user);
    }

    public function show($id)
    {
        $data = Game::find($id);
        if (!$data) {
            return response()->json(['message' => 'Game not found'], 404);
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = Game::create([
            'name' => $request->name,
            'link_image' => $request->link_image
        ]);

        return response()->json(['message' => 'Game created successfully', 'game' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $data = Game::find($id);
        if (!$data) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data->update([
            'name' => $request->name,
            'link_image' => $request->link_image
        ]);

        return response()->json(['message' => 'Game updated successfully', 'game' => $data]);
    }

    public function destroy($id)
    {
        $data = Game::find($id);
        if (!$data) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        $data->delete();
        return response()->json(['message' => 'Game deleted successfully']);
    }
}
