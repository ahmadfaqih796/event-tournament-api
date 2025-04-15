<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $keyword = $request->input('payment_status');
        $user = Financial::with(['transaction'])->get();
        return response()->json($user);
    }

    public function show($id)
    {
        $data = Financial::find($id);
        if (!$data) {
            return response()->json(['message' => 'Financial not found'], 404);
        }
        return response()->json($data);
    }
}
