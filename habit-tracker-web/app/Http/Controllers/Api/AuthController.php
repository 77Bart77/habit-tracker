<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ranking;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
//user z bazy ktory został uwierzytelniony
        $user = $request->user();

        // usuwamy starego usera
        $user->tokens()->delete();

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

   public function me(Request $request)
{
    $user = $request->user();

    $ranking = Ranking::query()
        ->where('user_id', $user->id)
        ->first();

    $totalPoints = (int)($ranking->total_points ?? 0);
    $level       = (int)($ranking->level ?? 1);

    return response()->json([
        'user'        => $user,
        'totalPoints' => $totalPoints,
        'level'       => $level
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
