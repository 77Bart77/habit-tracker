<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Goal;
use App\Models\GoalLike;
use App\Services\FriendshipService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show(User $user, FriendshipService $friendships)
    {
        $me = Auth::user();

        // publiczne cele usera
        $publicGoals = Goal::query()
            ->where('user_id', $user->id)
            ->where('is_public', true)
            ->with(['category', 'user', 'comments.user']) // żeby comments działały w widoku
            ->withCount('likes')
            ->latest()
            ->take(10)
            ->get();

        $likedGoalIds = GoalLike::query()
            ->where('user_id', $me->id)
            ->pluck('goal_id')
            ->toArray();

        $areFriends = $friendships->areFriends($me->id, $user->id);
        $hasPending = $friendships->hasPendingRequest($me->id, $user->id);

        // PUNKTY + LEVEL (rankings)
        $ranking = DB::table('rankings')
            ->where('user_id', $user->id)
            ->first();

        $totalPoints = (int)($ranking->total_points ?? 0);
        $level       = (int)($ranking->level ?? 1);

        // fallback jeśli nie ma w rankings (np. stary user)
        if (!$ranking) {
            $totalPoints = (int) DB::table('points_history')
                ->where('user_id', $user->id)
                ->sum('points');

            $level = 1;
        }

        return view('users.show', [
            'userProfile'  => $user,
            'publicGoals'  => $publicGoals,
            'areFriends'   => $areFriends,
            'hasPending'   => $hasPending,
            'likedGoalIds' => $likedGoalIds,

            
            'totalPoints'  => $totalPoints,
            'level'        => $level,
        ]);
    }
}
