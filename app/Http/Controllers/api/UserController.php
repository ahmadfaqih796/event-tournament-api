<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index()
    {
        $users = User::get();
        return response()->json($users);
    }

    public function show($id)
    {
        $users = User::find($id);
        if (!$users) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        $data = User::find($id);
        if (!$data) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data->update([
            'name' => $request->name,
            'role' => $request->role,
            'is_active' => $request->is_active
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $data]);
    }

    public function destroy($id)
    {
        $users = User::find($id);
        if (!$users) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $users->update([
            'is_active' => 0
        ]);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
