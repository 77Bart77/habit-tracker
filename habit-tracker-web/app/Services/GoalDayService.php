<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\GoalDay;
use App\Models\GoalAttachment;
use App\Services\PointService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class GoalDayService
{
    public function __construct(
        private PointService $pointService
    ) {}
public function getDayForDate(int $goalId, string $date): GoalDay
{
    // upewniamy się, że cel należy do usera
    Goal::query()
        ->where('id', $goalId)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $dayDate = Carbon::parse($date)->toDateString();

    return GoalDay::query()
        ->where('goal_id', $goalId)
        ->where('date', $dayDate)
        ->firstOrFail();
}

    /**
     * Oznacza wskazaną datę jako DONE (IDEMPOTENTNIE).
     * - jeśli już DONE -> nic nie zmienia
     * - brak cofania DONE (żeby nie psuć punktów / rankingu)
     */
    public function markDoneForDate(int $goalId, string $date): GoalDay
    {
        $goal = Goal::query()
            ->where('user_id', Auth::id())
            ->findOrFail($goalId);

        if ($goal->status !== Goal::STATUS_ACTIVE) {
            abort(403, 'Tylko aktywne cele mogą być odhaczane.');
        }

        $dayDate = Carbon::parse($date)->startOfDay();

        $start = Carbon::parse($goal->start_date)->startOfDay();
        $end   = Carbon::parse($goal->end_date)->startOfDay();

        if ($dayDate->lt($start) || $dayDate->gt($end)) {
            abort(403, 'Ta data nie należy do zakresu tego celu.');
        }

        $interval = max(1, (int) $goal->interval_days);
        $diff     = $start->diffInDays($dayDate);

        if ($diff % $interval !== 0) {
            abort(403, 'Ten cel nie jest zaplanowany do wykonania w tym dniu.');
        }

        // Rekord dnia musi istnieć (harmonogram generujesz w ensureAllDaysExist w GoalService)
        $day = GoalDay::query()
            ->where('goal_id', $goalId)
            ->where('date', $dayDate->toDateString())
            ->first();

        if (!$day) {
            abort(403, 'Brak wpisu w harmonogramie na ten dzień.');
        }

        // ✅ IDEMPOTENTNIE: jak już DONE, to zwracamy i tyle
        if ($day->status === GoalDay::STATUS_DONE) {
            return $day;
        }

        // Przejście na DONE
        $day->status = GoalDay::STATUS_DONE;
        $day->save();

        // Punkty (awardOncePerDay zabezpiecza przed dublem)
        $this->pointService->awardOncePerDay(
            Auth::id(),
            PointService::ACTION_DONE_TODAY,
            5,
            'goal',
            $goalId,
            $dayDate
        );

        // Bonusy za % (80/100)
        $this->handleGoalCompletionPoints($goalId);

        return $day;
    }

    /**
     * Ustaw status dla daty.
     * - dozwolone: pending/done/skipped
     * - DONE nie cofamy (żeby nie psuć punktów)
     */
    public function setStatusForDate(int $goalId, string $date, string $status): GoalDay
    {
        if (!in_array($status, [GoalDay::STATUS_PENDING, GoalDay::STATUS_DONE, GoalDay::STATUS_SKIPPED], true)) {
            abort(422, 'Niepoprawny status.');
        }

        // upewniamy się, że cel należy do usera
        Goal::query()
            ->where('id', $goalId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $dayDate = Carbon::parse($date)->toDateString();

        $day = GoalDay::query()
            ->where('goal_id', $goalId)
            ->where('date', $dayDate)
            ->firstOrFail();

        // ✅ blokada cofania DONE
        if ($day->status === GoalDay::STATUS_DONE && $status !== GoalDay::STATUS_DONE) {
            abort(403, 'Nie można cofnąć statusu DONE.');
        }

        // jeśli ustawiamy DONE, to zrób to tą samą logiką (punkty + walidacja harmonogramu)
        if ($status === GoalDay::STATUS_DONE) {
            return $this->markDoneForDate($goalId, $dayDate);
        }

        // pending / skipped
        $day->status = $status;
        $day->save();

        return $day;
    }

    /**
     * Ustaw notatkę dla daty + opcjonalny załącznik.
     */
    public function setNoteForDate(int $goalId, string $date, ?string $note, ?UploadedFile $file = null): GoalDay
    {
        // upewniamy się, że cel należy do usera
        Goal::query()
            ->where('id', $goalId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $dayDate = Carbon::parse($date)->toDateString();

        $day = GoalDay::query()
            ->where('goal_id', $goalId)
            ->where('date', $dayDate)
            ->firstOrFail();

        $day->note = $note;
        $day->save();

        if ($file) {
            $path = $file->store('goal_days', 'public');

            GoalAttachment::create([
                'goal_id'       => $goalId,
                'goal_day_id'   => $day->id,
                'user_id'       => Auth::id(),
                'file_path'     => $path,
                'mime_type'     => $file->getClientMimeType(),
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        $day->load('attachments');
        return $day;
    }

    public function saveNoteForToday(int $goalId, \Illuminate\Http\Request $request): GoalDay
{
    $data = $request->validate([
        'note'       => 'nullable|string|max:1000',
        'attachment' => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime|max:20480',
    ]);

    $today = Carbon::today()->toDateString();

    // notatka + (opcjonalnie) plik robimy przez gotową metodę z serwisu
    return $this->setNoteForDate(
        $goalId,
        $today,
        $data['note'] ?? null,
        $request->file('attachment')
    );
}

public function setNoteForDayId(int $dayId, ?string $note, ?UploadedFile $file = null): GoalDay
{
    $day = GoalDay::query()
        ->where('id', $dayId)
        ->whereHas('goal', fn($q) => $q->where('user_id', Auth::id()))
        ->firstOrFail();

    $day->note = $note;
    if (!$day->status) {
        $day->status = GoalDay::STATUS_PENDING;
    }
    $day->save();

    if ($file) {
        $path = $file->store('goal_days', 'public');

        GoalAttachment::create([
            'goal_id'       => $day->goal_id,
            'goal_day_id'   => $day->id,
            'user_id'       => Auth::id(),
            'file_path'     => $path,
            'mime_type'     => $file->getClientMimeType(),
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    return $day->load('attachments');
}

public function clearNoteForDayId(int $dayId): GoalDay
{
    $day = GoalDay::query()
        ->where('id', $dayId)
        ->whereHas('goal', fn($q) => $q->where('user_id', Auth::id()))
        ->firstOrFail();

    $day->note = null;
    $day->save();

    return $day;
}


    // =========================
    // Helpers: bonusy za % celu
    // =========================

    private function getGoalLengthDays(Goal $goal): int
    {
        $start = Carbon::parse($goal->start_date)->startOfDay();
        $end   = Carbon::parse($goal->end_date)->startOfDay();

        // +1 bo liczymy inkluzywnie
        return $start->diffInDays($end) + 1;
    }

    private function handleGoalCompletionPoints(int $goalId): void
    {
        $goal = Goal::with('days')->findOrFail($goalId);

        // blokada na bonusy: min 7 dni
        $lengthDays = $this->getGoalLengthDays($goal);
        if ($lengthDays < 7) {
            return;
        }

        $totalDays = $goal->days->count();
        if ($totalDays === 0) {
            return;
        }

        $doneDays = $goal->days
            ->where('status', GoalDay::STATUS_DONE)
            ->count();

        $percent = ($doneDays / $totalDays) * 100;

        if ($percent >= 80) {
            $this->pointService->awardOncePerDay(
                $goal->user_id,
                PointService::ACTION_GOAL_COMPLETED_80,
                50,
                'goal',
                $goal->id,
                Carbon::parse($goal->end_date)->startOfDay()
            );
        }

        if ($percent >= 100) {
            $this->pointService->awardOncePerDay(
                $goal->user_id,
                PointService::ACTION_GOAL_COMPLETED_100,
                100,
                'goal',
                $goal->id,
                Carbon::parse($goal->end_date)->startOfDay()
            );
        }
    }

    
}
