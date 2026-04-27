<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\GoalDay;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class GoalStatsService
{
    /**
     * Ogólny overview dla użytkownika – liczby na dashboard.
     */
    public function getOverviewForUser(int $userId): array
    {
        $activeCount   = Goal::query()
            ->where('user_id', $userId)
            ->where('status', Goal::STATUS_ACTIVE)
            ->count();

        $pausedCount   = Goal::query()
            ->where('user_id', $userId)
            ->where('status', Goal::STATUS_PAUSED)
            ->count();

        $finishedCount = Goal::query()
            ->where('user_id', $userId)
            ->where('status', Goal::STATUS_FINISHED)
            ->count();

        $today = Carbon::today();

        // Ile dni realizacji jest zaplanowanych na dziś (dla wszystkich celów usera)
        $todayPlannedDays = GoalDay::query()
            ->whereDate('date', $today)
            ->whereHas('goal', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->count();

        // Ile z tych dzisiejszych jest już oznaczonych jako DONE
        $todayDoneDays = GoalDay::query()
            ->whereDate('date', $today)
            ->where('status', GoalDay::STATUS_DONE)
            ->whereHas('goal', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->count();

        return [
            'active_goals'   => $activeCount,
            'paused_goals'   => $pausedCount,
            'finished_goals' => $finishedCount,
            'today_planned'  => $todayPlannedDays,
            'today_done'     => $todayDoneDays,
        ];
    }

    /**
     * Lista aktywnych celów użytkownika na dashboard (np. max 5).
     * Eager load 'days', żeby progress_percent nie robił dodatkowych zapytań.
     */
    public function getActiveGoalsForUser(int $userId, int $limit = 5): Collection
    {
        return Goal::query()
            ->where('user_id', $userId)
            ->where('status', Goal::STATUS_ACTIVE)
            ->with(['category', 'days'])   // days potrzebne do progress_percent
            ->orderBy('end_date')          // najbliższe do końca najpierw
            ->limit($limit)
            ->get();
    }

    /**
     * Publiczne cele na dashboard (np. inspiracje).
     */
    public function getPublicGoalsForDashboard(int $currentUserId, int $limit = 5): Collection
{
    return Goal::query()
        ->where('is_public', true)
        ->where('status', Goal::STATUS_ACTIVE)
        ->where('user_id', '!=', $currentUserId)    //  nie pokazujemy własnych
        ->with(['category', 'days', 'user'])   
        ->withCount('likes')
     // user -> autor celu
        ->orderByDesc('updated_at')
        ->limit($limit)
        ->get();
}

}
