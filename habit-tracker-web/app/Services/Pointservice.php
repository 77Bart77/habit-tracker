<?php

namespace App\Services;

use App\Models\PointsHistory;
use App\Models\Ranking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;


class PointService
{
    // akcje za co pkt
    public const ACTION_DONE_TODAY        = 'done_today';
    public const ACTION_GOAL_COMPLETED_80 = 'goal_completed_80';
    public const ACTION_GOAL_COMPLETED_100 = 'goal_completed_100';
    public const ACTION_LIKE              = 'like';
    public const ACTION_VERIFIED_80       = 'verified_80';
    public const ACTION_PRO_VERIFIED = 'pro_verified';


    // levele 
    private const LEVEL_THRESHOLDS = [
        1  => 0,
        2  => 100,
        3  => 250,
        4  => 500,
        5  => 900,
        6  => 1400,
        7  => 2000,
        8  => 2800,
        9  => 3800,
        10 => 5000,
    ];

    
     // dodaje wpis do points_history i aktualizuje rankings
     
    public function award(
        int $userId,
        string $action,
        int $points,
        ?string $relatedType = null,
        ?int $relatedId = null,
        ?Carbon $eventDate = null,
        ?int $actorUserId = null
    ): void {
        $eventDate = $eventDate ?: now()->startOfDay();

        DB::transaction(function () use ($userId, $action, $points, $relatedType, $relatedId, $eventDate, $actorUserId) {

            PointsHistory::create([
                'user_id'       => $userId,
                'actor_user_id' => $actorUserId,
                'event_date'    => $eventDate->toDateString(),
                'action'        => $action,
                'related_type'  => $relatedType,
                'related_id'    => $relatedId,
                'points'        => $points,
                'created_at'    => now(),
            ]);

            $this->recalculateRankingForUser($userId);
        });
    }



   //tylko raz dziennie zabezpieczenie 


    public function awardOncePerDay(
        int $userId,
        string $action,
        int $points,
        ?string $relatedType,
        ?int $relatedId,
        ?Carbon $eventDate = null
    ): bool {
        $eventDate = $eventDate ?: now()->startOfDay();

        try {
            // próbujemy od razu zrobić wpis , baza unique blokuje duplikat
            PointsHistory::create([
                'user_id'      => $userId,
                'event_date'   => $eventDate->toDateString(),
                'action'       => $action,
                'related_type' => $relatedType,
                'related_id'   => $relatedId,
                'points'       => $points,
                'created_at'   => now(),
            ]);

            // jak sie udalo przelicz ranking
            $this->recalculateRankingForUser($userId);

            return true;
        } catch (QueryException $e) {
            // MySQL duplicate naruszenie zasad bazy lub duplikat entry
            if (($e->errorInfo[0] ?? null) === '23000' || ($e->errorInfo[1] ?? null) === 1062) {
                return false;
            }
            //jezeli inny nie ukrywamy
            throw $e;
        }
    }


    
    //liczymy punkty i level
    public function recalculateRankingForUser(int $userId): void
    {
        // suma punktów z historii 
        $totalPoints = (int) PointsHistory::query()
            ->where('user_id', $userId)
            ->sum('points');
//obliczanie level
        $level = $this->calculateLevel($totalPoints);
//update albo tworzymy jak nie ma 
        Ranking::query()->updateOrCreate(
            ['user_id' => $userId],
            [
                'total_points' => $totalPoints,
                'level'        => $level,
                'last_update'  => now(),
            ]
        );
    }

    
    //liczymy level
    public function calculateLevel(int $totalPoints): int
    {
        $level = 1;

        foreach (self::LEVEL_THRESHOLDS as $lvl => $minPoints) {
            if ($totalPoints >= $minPoints) {
                $level = $lvl;
            }
        }

        return $level;
    }

    //ile do next lvl
    public function pointsToNextLevel(int $totalPoints): ?int
    {
        //wyliczamy aktualny
        $current = $this->calculateLevel($totalPoints);
        //obliczamy nastepny
        $next = $current + 1;
//sprawdzamy czy jest kolejny lvl
        if (!isset(self::LEVEL_THRESHOLDS[$next])) {
            return null; // max level
        }
//obliczamy punkty wymagane na next minus aktualne 
        return max(0, self::LEVEL_THRESHOLDS[$next] - $totalPoints);
    }

    //helper, sprawdzamy czy jestesmy w tranzakcji

    private function runInTransaction(callable $callback): mixed
    {
        // Jeśli już jesteśmy w transakcji, NIE odpalamy kolejnej
        if (DB::transactionLevel() > 0) {
            return $callback();
        }

        // Jeśli nie ma transakcji, odpalamy ją tutaj
        return DB::transaction($callback);
    }
}
