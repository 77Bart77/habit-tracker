<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\GoalDay;
use App\Models\GoalAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GoalService
{
    

    // Aktywne cele zalogowanego usera
    public function getAll(): Collection
    {
        $this->closeExpiredGoals();

        return Goal::query()
            ->where('user_id', Auth::id())
            ->where('status', Goal::STATUS_ACTIVE)
            ->with(['category', 'days'])
            ->latest()
            ->get();
    }

    // Pojedynczy cel 
    public function getById(int $id): Goal
    {
        return Goal::query()
            ->where('user_id', Auth::id())
            ->with('category')
            ->findOrFail($id);
    }

    // Filtrowanie
    public function getFilteredGoals(array $filters = []): Collection
    {
        $this->closeExpiredGoals();

        $query = Goal::query()
            ->where('user_id', Auth::id())
            ->with(['category', 'days', 'attachments']);

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('goal_category_id', (int) $filters['category_id']);
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', (array) $filters['status']);
        } else {
            $query->whereIn('status', [
                Goal::STATUS_ACTIVE,
                Goal::STATUS_FINISHED,
            ]);
        }

        return $query->latest()->get();
    }

    public function getPausedGoals(): Collection
    {
        return Goal::query()
            ->where('user_id', Auth::id())
            ->where('status', Goal::STATUS_PAUSED)
            ->with(['category', 'days', 'attachments'])
            ->latest()
            ->get();
    }

    // Cel z osią czasu + relacjami
    public function getWithDays(int $id): Goal
    {
        $goal = Goal::query()
            ->where('user_id', Auth::id())
            ->withCount('likes')
            ->with([
                'proRequest',
                'category',
                'attachments',            // główne załączniki celu
                'comments.user',
                'days' => fn ($q) => $q
                    ->orderBy('date')
                    ->with('attachments'), // załączniki do dni
            ])
            ->findOrFail($id);

        // Uzupełnij harmonogram 
        $this->ensureAllDaysExist($goal);

        // Doładuj świeże dane
        $goal->load([
            'category',
            'attachments',
            'comments.user',
            'days' => fn ($q) => $q
                ->orderBy('date')
                ->with('attachments'),
        ]);

        return $goal;
    }

    //crud

    public function create(Request $request): Goal
    {
        $data = $request->validate([
            'goal_category_id' => 'required|integer|exists:goal_categories,id',
            'title'            => 'required|string|max:100',
            'description'      => 'nullable|string',

            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'interval_days'    => 'required|integer|min:1|max:365',

            'is_public'        => 'sometimes|boolean',
            'status'           => 'sometimes|in:' . implode(',', [
                Goal::STATUS_ACTIVE,
                Goal::STATUS_FINISHED,
                Goal::STATUS_PAUSED,
            ]),

            'attachment' => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime|max:20480',
        ]);

        $goalData = $data;
        unset($goalData['attachment']);

        $goal = new Goal($goalData);
        $goal->user_id = Auth::id();
        $goal->status  = $goal->status ?? Goal::STATUS_ACTIVE;
        $goal->save();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('goals', 'public');

            GoalAttachment::create([
                'goal_id'       => $goal->id,
                'goal_day_id'   => null,
                'user_id'       => Auth::id(),
                'file_path'     => $path,
                'mime_type'     => $file->getClientMimeType(),
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        $goal->load('days');
        $this->ensureAllDaysExist($goal);

        return $goal;
    }

    public function update(Request $request, int $id): Goal
    {
        $goal = Goal::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $originalStart    = $goal->start_date?->toDateString();
        $originalEnd      = $goal->end_date?->toDateString();
        $originalInterval = (int) $goal->interval_days;

        $data = $request->validate([
            'goal_category_id' => 'sometimes|integer|exists:goal_categories,id',
            'title'            => 'sometimes|string|max:100',
            'description'      => 'nullable|string',

            'interval_days'    => 'sometimes|integer|min:1|max:365',
            'start_date'       => 'sometimes|date',
            'end_date'         => 'sometimes|date|after_or_equal:start_date',

            'is_public'        => 'sometimes|boolean',
            'status'           => 'sometimes|in:' . implode(',', [
                Goal::STATUS_ACTIVE,
                Goal::STATUS_FINISHED,
                Goal::STATUS_PAUSED,
            ]),

            'attachment'       => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime|max:20480',
        ]);

        $goalData = $data;
        unset($goalData['attachment']);

        // Jeżeli edytujesz wstrzymany cel, to traktujemy to jako wznowienie
        if ($goal->status === Goal::STATUS_PAUSED) {
            $goalData['status'] = Goal::STATUS_ACTIVE;
        }

        $goal->fill($goalData)->save();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('goals', 'public');

            GoalAttachment::create([
                'goal_id'       => $goal->id,
                'goal_day_id'   => null,
                'user_id'       => Auth::id(),
                'file_path'     => $path,
                'mime_type'     => $file->getClientMimeType(),
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        $goal->refresh();

        $newStart    = $goal->start_date?->toDateString();
        $newEnd      = $goal->end_date?->toDateString();
        $newInterval = (int) $goal->interval_days;

        $datesChanged = (
            $originalStart    !== $newStart ||
            $originalEnd      !== $newEnd ||
            $originalInterval !== $newInterval
        );

        if ($datesChanged) {
            // reset harmonogramu
            $goal->days()->delete();
            $goal->setRelation('days', collect());
            $this->ensureAllDaysExist($goal);
            $goal->refresh();
        }

        return $goal;
    }

    // Wstrzymanie
    public function deactivate(int $id): void
    {
        $goal = Goal::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $goal->status = Goal::STATUS_PAUSED;
        $goal->save();
    }

    // Wznowienie
    public function resume(int $id): void
    {
        $goal = Goal::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($goal->status === Goal::STATUS_PAUSED) {
            $goal->status = Goal::STATUS_ACTIVE;
            $goal->save();
        }
    }

    // Trwałe usunięcie celu 
    public function delete(int $id): void
    {
        $goal = Goal::query()
            ->where('user_id', Auth::id())
            ->with(['days.attachments', 'attachments'])
            ->findOrFail($id);

        foreach ($goal->days as $day) {
            $day->attachments()->delete();
        }

        $goal->days()->delete();
        $goal->attachments()->delete();
        $goal->delete();
    }

    

    public function getExecutionHistory(): Collection
    {
        return GoalDay::query()
            ->with('goal')
            ->whereHas('goal', fn ($q) => $q->where('user_id', Auth::id()))
            ->orderByDesc('date')
            ->get();
    }

    public function getPublicGoals(): Collection
    {
        return Goal::query()
            ->with(['category', 'days', 'user', 'comments.user'])
            ->withCount('likes')
            ->where('status', Goal::STATUS_ACTIVE)
            ->where('is_public', true)
            ->where('user_id', '!=', Auth::id())
            ->latest()
            ->get();
    }

    public function getPublicGoalById(int $id): Goal
    {
        return Goal::query()
            ->where('is_public', true)
            ->with(['category', 'days', 'user'])
            ->withCount('likes')
            ->findOrFail($id);
    }

    public function closeExpiredGoals(): int
    {
        return Goal::query()
            ->where('user_id', Auth::id())
            ->where('status', Goal::STATUS_ACTIVE)
            ->where('end_date', '<', Carbon::today())
            ->update(['status' => Goal::STATUS_FINISHED]);
    }

    //helper harmonogram

    private function ensureAllDaysExist(Goal $goal): void
    {
        $start    = Carbon::parse($goal->start_date)->startOfDay();
        $end      = Carbon::parse($goal->end_date)->startOfDay();
        $interval = max(1, (int) $goal->interval_days);

        $existing = $goal->days
            ->pluck('date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->all();

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $dateStr = $d->toDateString();
            $diff    = $start->diffInDays($d);

            if ($diff % $interval !== 0) {
                continue;
            }

            if (!in_array($dateStr, $existing, true)) {
                $goal->days()->create([
                    'date'   => $dateStr,
                    'status' => GoalDay::STATUS_PENDING,
                    'note'   => null,
                ]);
            }
        }
    }

    public function prepareForShow(Goal $goal, Carbon $today): array
{
    $minPercentForProRequest = 10;

    // status label + kolor
    [$statusLabel, $statusColor] = match ($goal->status) {
        Goal::STATUS_ACTIVE   => ['Aktywny', 'success'],
        Goal::STATUS_PAUSED   => ['Wstrzymany', 'secondary'],
        Goal::STATUS_FINISHED => ['Zakończony', 'info'],
        default               => ['Inny', 'dark'],
    };

    // proceny
    $percent = (int) ($goal->progress_percent ?? 0);

    // interval label
    $intervalDays = (int) ($goal->interval_days ?? 1);
    $intervalLabel = match ($intervalDays) {
        1  => 'Codziennie',
        2  => 'Co 2 dni',
        3  => 'Co 3 dni',
        7  => 'Raz w tygodniu',
        14 => 'Co 2 tygodnie',
        30 => 'Raz w miesiącu',
        default => 'Co '.$intervalDays.' dni',
    };

    // today day i done
    $todayDay = $goal->days?->first(fn ($day) => $day->date && $day->date->isSameDay($today));
    $todayDone = (bool) ($todayDay && $todayDay->status === GoalDay::STATUS_DONE);

    // PRO section visibility
    $proReq = $goal->proRequest ?? null;

    $hasDates = !empty($goal->start_date) && !empty($goal->end_date);

    $durationDays = null;
    if ($hasDates) {
        $durationDays = Carbon::parse($goal->start_date)
                ->diffInDays(Carbon::parse($goal->end_date)) + 1;
    }

    $ended = $hasDates ? Carbon::today()->gte(Carbon::parse($goal->end_date)) : false;

    $canShowProSection = $hasDates
        && ($durationDays ?? 0) >= 1
        && $ended
        && ($percent >= $minPercentForProRequest);

    $proBadge = null;
    if ($proReq) {
        $proBadge = match ($proReq->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default    => 'secondary',
        };
    }

    // timelineDays: gotowe dane pod UI (bez obliczeń w Blade)
    $timelineDays = ($goal->days ?? collect())
        ->sortBy('date')
        ->values()
        ->map(function ($day) use ($today) {
            $date = $day->date;
            $isPast = $date?->lt($today);

            if ($day->status === GoalDay::STATUS_DONE) {
                $label = 'Wykonano';
                $badge = 'success';
            } elseif ($isPast) {
                $label = 'Niezrealizowano';
                $badge = 'danger';
            } else {
                $label = 'Oczekuje';
                $badge = 'secondary';
            }

            // d pola do obiektu 
            $day->formatted = $date?->format('d.m.Y') ?? '-';
            $day->label = $label;
            $day->badge = $badge;

            $day->attachmentsVm = ($day->attachments ?? collect())
            ->map(fn ($att) => $this->mapAttachmentVm($att))
            ->values();

            return $day;
        });

    return [
        'minPercentForProRequest' => $minPercentForProRequest,

        'statusLabel' => $statusLabel,
        'statusColor' => $statusColor,

        'percent' => $percent,
        'intervalLabel' => $intervalLabel,

        'todayDay' => $todayDay,
        'todayDone' => $todayDone,

        'proReq' => $proReq,
        'proBadge' => $proBadge,
        'canShowProSection' => $canShowProSection,

        'timelineDays' => $timelineDays,
    ];
}

private function attachmentKind(?string $mime): string
{
    if (!$mime) return 'file';

    if (str_starts_with($mime, 'image/')) return 'image';
    if (str_starts_with($mime, 'video/')) return 'video';

    return 'file';
}

private function mapAttachmentVm($att): array
{
    return [
        'id'   => $att->id,
        'kind' => $this->attachmentKind($att->mime_type ?? null),
        'url'  => Storage::url($att->file_path),
        'name' => $att->original_name ?? 'plik',
        'mime' => $att->mime_type ?? null,
    ];
}
    
    
}
