<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\ProRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Services\PointService;

class ProRequestService


{

    public function __construct(private PointService $pointService) {}

    //sprawdzamy czy user ma prawo do pro
    public function canRequestPro(Goal $goal): array
    {
        // tylko wlasciciel moze zglosic 
        if ((int)$goal->user_id !== (int)Auth::id()) {
            return [false, 'To nie jest Twój cel.'];
        }

        // min 7 dni 
        if (!$goal->start_date || !$goal->end_date) {
            return [false, 'Cel musi mieć datę startu i końca.'];
        }

        $days = Carbon::parse($goal->start_date)->diffInDays(Carbon::parse($goal->end_date)) + 1;
        if ($days < 1) {
            //zmiana do testu
            return [false, 'Cel musi trwać minimum 7 dni.'];
        }

        // tylko po end date 
        if (Carbon::today()->lt(Carbon::parse($goal->end_date))) {
            return [false, 'Możesz zgłosić PRO dopiero po zakończeniu celu.'];
        }


        //  10% ukończenia do testu
        if ((int)$goal->progress_percent < 10) {
            return [false, 'Aby zgłosić PRO, cel musi mieć min. 10% (TEST).'];
        }


        // tylko jedno zgloszenie 
        $exists = ProRequest::query()->where('goal_id', $goal->id)->exists();
        if ($exists) {
            return [false, 'To zgłoszenie już istnieje.'];
        }

        return [true, 'OK'];
    }
    //tworzymy zgloszenie pro , zwraca obiekt prorequest
    public function createForGoal(int $goalId): ProRequest
    {
        //pobieramy cel 
        $goal = Goal::query()->findOrFail($goalId);

        [$ok, $msg] = $this->canRequestPro($goal);
        abort_unless($ok, 403, $msg);
        //tworzymy zglozenie 
        return ProRequest::create([
            'goal_id'       => $goal->id,
            'user_id'       => $goal->user_id,
            'status'        => ProRequest::STATUS_PENDING,
            'requested_at'  => now(),
        ]);
    }

    public function approve(int $proRequestId, ?string $adminNote = null, int $bonusPoints = 100): void
    {
        //tranzakcja bo update statusu i pkt 
        DB::transaction(function () use ($proRequestId, $adminNote, $bonusPoints) {
            //pobieramy i blokujemy
            $req = ProRequest::query()->lockForUpdate()->findOrFail($proRequestId);
            //sprawdzanie statusu
            if ($req->status !== ProRequest::STATUS_PENDING) {
                return;
            }
            //update zgloszenia na approved 
            $req->update([
                'status'      => ProRequest::STATUS_APPROVED,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_note'  => $adminNote,
            ]);

            // bonus za pro 
            $this->pointService->award(
                userId: $req->user_id,
                action: PointService::ACTION_PRO_VERIFIED,
                points: $bonusPoints,
                relatedType: 'pro_request',
                relatedId: $req->id,
                eventDate: now()->startOfDay(),
                actorUserId: Auth::id()
            );
        });
    }


    public function reject(int $proRequestId, ?string $adminNote = null): void
    {
        DB::transaction(function () use ($proRequestId, $adminNote) {

            $req = ProRequest::query()
                ->lockForUpdate()
                ->findOrFail($proRequestId);

            // jeśli już nie pending,nie ruszam
            if ($req->status !== ProRequest::STATUS_PENDING) {
                return;
            }

            $req->update([
                'status'      => ProRequest::STATUS_REJECTED,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_note'  => $adminNote,
            ]);
        });
    }
}
