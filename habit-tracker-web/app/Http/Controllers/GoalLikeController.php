<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalLike;
use App\Services\FriendshipService;
use Illuminate\Support\Facades\Auth;
use App\Services\PointService;


class GoalLikeController extends Controller
{

    public function __construct(
        private PointService $pointService
    ) {}
   public function toggle(Goal $goal, FriendshipService $friendships)
{
    $me = Auth::id();

    // like tylko dla publicznych celów
    if (!$goal->is_public) {
        abort(403);
    }

    // jeśli ktoś kogoś zablokował — brak interakcji
    if ($friendships->isBlockedEitherWay($me, (int) $goal->user_id)) {
        abort(403);
    }

    $existing = GoalLike::query()
        ->where('goal_id', $goal->id)
        ->where('user_id', $me)
        ->first();

    // UNLIKE: tylko usuwamy like, NIE cofamy punktów
    if ($existing) {
        $existing->delete();
        return back();
    }

    // LIKE: tworzymy rekord polubienia
    GoalLike::query()->create([
        'goal_id' => $goal->id,
        'user_id' => $me,
    ]);

    // ✅ PUNKTY ZA LIKE -> dostaje WŁAŚCICIEL celu, a actor_user_id to LIKER
    $ownerId = (int) $goal->user_id;

    if ($ownerId !== $me) {
        try {
            $this->pointService->award(
                $ownerId,                      // kto dostaje punkty (właściciel celu)
                PointService::ACTION_LIKE,      // akcja
                1,                              // punkty
                'goal',                         // related_type
                $goal->id,                      // related_id
                now()->startOfDay(),            // event_date
                $me                             // ✅ actor_user_id (kto dał like)
            );
        } catch (\Illuminate\Database\QueryException $e) {
            // jeśli już było (unikalny indeks), to ignorujemy
        }
    }

    return back();
}

}
