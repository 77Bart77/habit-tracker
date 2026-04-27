<?php

namespace App\Http\Controllers;

use App\Services\GoalStatsService;
use Illuminate\Support\Facades\Auth;
use App\Services\ChallengeService;

class DashboardController extends Controller
{
    private GoalStatsService $stats;

    public function __construct(GoalStatsService $stats)
    {
        $this->stats = $stats;
    }

    public function index(ChallengeService $challengeService)
{
    $userId = Auth::id();

    return view('dashboard', [
        'overview'    => $this->stats->getOverviewForUser($userId),
        'activeGoals' => $this->stats->getActiveGoalsForUser($userId, 5),
        'publicGoals' => $this->stats->getPublicGoalsForDashboard($userId, 5),

        // 👇 NOWE
        'challengeInvitesCount' => $challengeService->countMyPendingInvites(),
    ]);
}


}
