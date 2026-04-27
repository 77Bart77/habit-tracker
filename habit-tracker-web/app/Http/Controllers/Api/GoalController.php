<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\GoalDay;
use App\Services\GoalService;
use App\Services\GoalDayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function __construct(
        private GoalService $service,
        private GoalDayService $dayService
    ) {}

    // GET /api/goals
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category_id', 'status']);

        return response()->json(
            $this->service->getFilteredGoals($filters)
        );
    }

    // GET /api/goals/{goal}
    public function show(Goal $goal)
    {
        $this->authorizeOwner($goal);

        return response()->json(
            $this->service->getWithDays($goal->id)
        );
    }

    // POST /api/goals
    public function store(Request $request)
    {
        $goal = $this->service->create($request);

        return response()->json($goal, 201);
    }

    // PUT/PATCH /api/goals/{goal}
    public function update(Request $request, Goal $goal)
    {
        $this->authorizeOwner($goal);

        $goal = $this->service->update($request, $goal->id);

        return response()->json($goal);
    }

    // DELETE /api/goals/{goal}
    public function destroy(Goal $goal)
    {
        $this->authorizeOwner($goal);

        $this->service->delete($goal->id);

        return response()->json(['ok' => true]);
    }

    // =========================
    // DAYS
    // =========================

    // GET /api/goals/{goal}/days
    public function days(Goal $goal)
    {
        $this->authorizeOwner($goal);

        // zwracamy dni posortowane po dacie
        $full = $this->service->getWithDays($goal->id);

        return response()->json(
            $full->days->sortBy('date')->values()
        );
    }

    // PATCH /api/goals/{goal}/days/{date}/done
    public function markDoneForDate(Goal $goal, string $date)
    {
        $this->authorizeOwner($goal);

        // UWAGA: u Ciebie to jest toggle (done <-> pending)
        $day = $this->dayService->markDoneForDate($goal->id, $date);

        return response()->json($day);
    }

    // PATCH /api/goals/{goal}/days/{date}/status
   public function setStatusForDate(Request $request, Goal $goal, string $date)
{
    $this->authorizeOwner($goal);

    $data = $request->validate([
        'status' => ['required', 'in:' . implode(',', [
            GoalDay::STATUS_PENDING,
            GoalDay::STATUS_DONE,
            GoalDay::STATUS_SKIPPED,
        ])],
    ]);

    $day = $this->dayService->setStatusForDate($goal->id, $date, $data['status']);

    return response()->json($day);
}


    // PATCH /api/goals/{goal}/days/{date}/note
   public function setNoteForDate(Request $request, Goal $goal, string $date)
{
    $this->authorizeOwner($goal);

    $data = $request->validate([
        'note'       => ['nullable', 'string', 'max:1000'],
        'attachment' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime', 'max:20480'],
    ]);

    $day = $this->dayService->setNoteForDate(
        $goal->id,
        $date,
        $data['note'] ?? null,
        $request->file('attachment')
    );

    return response()->json($day);
}

// DELETE /api/goals/{goal}/days/{date}/note
public function clearNoteForDate(Goal $goal, string $date)
{
    $this->authorizeOwner($goal);

    $day = $this->dayService->getDayForDate($goal->id, $date);
    $day = $this->dayService->clearNoteForDayId($day->id);

    return response()->json($day);
}

// GET /api/public-goals
public function publicIndex()
{
    return response()->json(
        $this->service->getPublicGoals()
    );
}

    private function authorizeOwner(Goal $goal): void
    {
        abort_if((int)$goal->user_id !== (int)Auth::id(), 403, 'To nie jest Twój cel.');
    }
}
