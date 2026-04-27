<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PointService;

class ChallengeService
{
    private PointService $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

   

    //oznaczamy jako finished jezeli sie skonczyl 
    private function closeExpiredChallengesForUser(): int
    {
        return DB::table('challenge_participants as cp')
            ->join('challenges as c', 'c.id', '=', 'cp.challenge_id')
            ->where('cp.user_id', Auth::id())
            ->where('cp.status', 'active')
            ->where('c.end_date', '<', Carbon::today()->toDateString())
            ->update(['cp.status' => 'finished']);
    }

    /*
    |--------------------------------------------------------------------------
    | 1) TWORZENIE CHALLENGE + GENEROWANIE DNI
    |--------------------------------------------------------------------------
    */

   
    //generuje dni dla usera, dla owner po utworzeniu dla usera po akceptacji
    public function generateDaysForUser(int $challengeId, int $userId): void
    {
        //pobieramy challenge
        $challenge = DB::table('challenges')
            ->select('id', 'start_date', 'end_date')
            ->where('id', $challengeId)
            ->first();
//wlidacja 
        abort_if(!$challenge, 404, 'Challenge not found');
        abort_if(empty($challenge->start_date) || empty($challenge->end_date), 422, 'Challenge must have start_date and end_date');
//zmieniamy na carbon bez minut i godzin 
        $start = Carbon::parse($challenge->start_date)->startOfDay();
        $end   = Carbon::parse($challenge->end_date)->startOfDay();
//sprawdzanie logiki dat
        abort_if($end->lt($start), 422, 'end_date cannot be earlier than start_date');
//tablica na dni
        $rows = [];
        //bierzemy aktualny czas
        $now  = now();
//lecimy po dniach 
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $rows[] = [
                'challenge_id' => $challengeId,
                'user_id'      => $userId,
                'date'         => $d->toDateString(),
                'status'       => 'pending',
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }
//zapis
        DB::table('challenge_days')->insertOrIgnore($rows);
    }

    
    //tworzymy challange i zwraca id, owner jako active  plus generacja dni plus zaproszenia pending
    public function create(Request $request): int
    {
        $data = $request->validate([
            'goal_category_id'   => 'required|integer|exists:goal_categories,id',
            'title'              => 'required|string|max:150',
            'description'        => 'nullable|string',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after_or_equal:start_date',
            'is_public'          => 'sometimes|boolean',
            'invited_user_ids'   => 'nullable|array',
            'invited_user_ids.*' => 'integer|exists:users,id',
            'attachment'         => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime|max:20480',
        ]);

        return DB::transaction(function () use ($data, $request) {

            //  create challenge
            $challengeId = DB::table('challenges')->insertGetId([
                'title'            => $data['title'],
                'description'      => $data['description'] ?? null,
                'goal_category_id' => $data['goal_category_id'],
                'created_by'       => Auth::id(),
                'start_date'       => $data['start_date'],
                'end_date'         => $data['end_date'],
                'is_public'        => (int)($data['is_public'] ?? 0),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // upload pliku
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('challenges', 'public');

                DB::table('challenge_attachments')->insert([
                    'challenge_id'  => $challengeId,
                    'user_id'       => Auth::id(),
                    'file_path'     => $path,
                    'mime_type'     => $file->getClientMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }

            // owner jako uczestnik active
            DB::table('challenge_participants')->insert([
                'challenge_id' => $challengeId,
                'user_id'      => Auth::id(),
                'status'       => 'active',
                'joined_at'    => now(),
            ]);

            //  dni dla ownera
            $this->generateDaysForUser($challengeId, Auth::id());

            // zaproszenia (pending), czyscimy zduplikatow, tylko int , wykluczamy siebie 
            $inviteIds = array_unique(array_map('intval', $data['invited_user_ids'] ?? []));
            $inviteIds = array_values(array_filter($inviteIds, fn($id) => $id !== Auth::id())); // nie zapraszaj siebie
//jesli lista nie jest pusta 
            if (!empty($inviteIds)) {
                $rows = [];
                $now = now();

                foreach ($inviteIds as $uid) {
                    $rows[] = [
                        'challenge_id'    => $challengeId,
                        'invited_user_id' => $uid,
                        'invited_by'      => Auth::id(),
                        'status'          => 'pending',
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }

                DB::table('challenge_invites')->insertOrIgnore($rows);
            }

            return $challengeId;
        });
    }

    //zwraca liste wszystkich oczekujacych

    public function getMyInvites()
    {
        return DB::table('challenge_invites as ci')
            ->join('challenges as c', 'c.id', '=', 'ci.challenge_id')
            ->join('users as u', 'u.id', '=', 'ci.invited_by')
            ->select([
                'ci.id as invite_id',
                'ci.challenge_id',
                'ci.status as invite_status',
                'ci.created_at as invited_at',
                'c.title',
                'c.start_date',
                'c.end_date',
                'u.name as invited_by_name',
            ])
            ->where('ci.invited_user_id', Auth::id())
            ->where('ci.status', 'pending')
            ->orderByDesc('ci.id')
            ->get();
    }
//akceptacja
    public function acceptInvite(int $inviteId): int
    {
        //tarnzakcja bo akceptujesz, dodajesz uczestnictwo plus generujesz dni 
        return DB::transaction(function () use ($inviteId) {
//pobieramy invite, blokujemy przed np klikieciem dwa razy
            $invite = DB::table('challenge_invites')
                ->where('id', $inviteId)
                ->where('invited_user_id', Auth::id())
                ->lockForUpdate()
                ->first();
//walidacja 
            abort_if(!$invite, 404, 'Invite not found');
            abort_if($invite->status !== 'pending', 422, 'Invite is not pending');
//zmienamy ststus na acepted
            DB::table('challenge_invites')
                ->where('id', $inviteId)
                ->update([
                    'status'     => 'accepted',
                    'updated_at' => now(),
                ]);
//dodanie usera do uczestnikow 
            DB::table('challenge_participants')->insertOrIgnore([
                'challenge_id' => (int)$invite->challenge_id,
                'user_id'      => Auth::id(),
                'status'       => 'active',
                'joined_at'    => now(),
            ]);
//gnetujemy dni dla uera
            $this->generateDaysForUser((int)$invite->challenge_id, Auth::id());
//zwracamy challange id
            return (int)$invite->challenge_id;
        });
    }
//odrzucenie
    public function declineInvite(int $inviteId): void
    {
        $updated = DB::table('challenge_invites')
            ->where('id', $inviteId)
            ->where('invited_user_id', Auth::id())
            ->where('status', 'pending')
            ->update([
                'status'     => 'declined',
                'updated_at' => now(),
            ]);

        abort_if($updated === 0, 404, 'Invite not found or not pending');
    }
//liczmy zproszenia 
    public function countMyPendingInvites(): int
    {
        return (int) DB::table('challenge_invites')
            ->where('invited_user_id', Auth::id())
            ->where('status', 'pending')
            ->count();
    }

   //lista challenges active 
   public function getMyChallenges()
{
    //zamykamy 
    $this->closeExpiredChallengesForUser();
//pobieramy
    return DB::table('challenge_participants as cp')
        ->join('challenges as c', 'c.id', '=', 'cp.challenge_id')
        ->leftJoin('users as u', 'u.id', '=', 'c.created_by')
        ->where('cp.user_id', Auth::id())
        ->whereIn('cp.status', ['active', 'finished'])
        ->select([
            'c.id',
            'c.title',
            'c.start_date',
            'c.end_date',
            'u.name as owner_name',
            'cp.status as participant_status',
        ])
        ->orderByDesc('c.id')
        ->get();
}


    //szczegoly show 

    public function getChallengeDetails(int $challengeId): array
    {
        //szukamy challenge po id 
        $challenge = DB::table('challenges')->where('id', $challengeId)->first();
        abort_if(!$challenge, 404, 'Challenge not found');

        // sprawdzam czy aktualny user ma wpis, pobieramy tylko status
        $myParticipantStatus = DB::table('challenge_participants')
            ->where('challenge_id', $challengeId)
            ->where('user_id', Auth::id())
            ->value('status');

        abort_if(!$myParticipantStatus, 403, 'Not allowed');

        // normalizujemy daty bez godzin 
        $today = Carbon::today()->startOfDay();
        $start = Carbon::parse($challenge->start_date)->startOfDay();
        $end   = Carbon::parse($challenge->end_date)->startOfDay();
        //flgi pod ui
        $canToggleToday = $today->betweenIncluded($start, $end);
        $isExpired      = $today->gt($end);
        $todayStr       = $today->toDateString();

        // pobieram uczestnikow 
        $participants = DB::table('challenge_participants as cp')
            ->join('users as u', 'u.id', '=', 'cp.user_id')
            ->where('cp.challenge_id', $challengeId)
            ->whereIn('cp.status', ['active', 'finished'])
            ->select(['u.id as user_id', 'u.name'])
            ->orderBy('u.name')
            ->get();

        // statystyki dla kazdego usera  ile dni zrobił ile wszystkich dni
        $stats = DB::table('challenge_days')
            ->select([
                'user_id',
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as done"),
            ])
            ->where('challenge_id', $challengeId)
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');
        //tablica na wynik 
        $rows = [];
        //lecimy przez wszystkich user challenge i ponieramy dane 
        foreach ($participants as $p) {
            $s = $stats->get($p->user_id);

            $total   = (int)($s->total ?? 0);
            $done    = (int)($s->done ?? 0);
            $percent = $total > 0 ? (int) round(($done / $total) * 100) : 0;

            $todayStatus = DB::table('challenge_days')
                ->where('challenge_id', $challengeId)
                ->where('user_id', $p->user_id)
                ->where('date', $todayStr)
                ->value('status');

            $rows[] = [
                'user_id'      => $p->user_id,
                'name'         => $p->name,
                'done'         => $done,
                'total'        => $total,
                'percent'      => $percent,
                'today_status' => $todayStatus,
            ];
        }

        // mój status na dziś
        $myTodayStatus = DB::table('challenge_days')
            ->where('challenge_id', $challengeId)
            ->where('user_id', Auth::id())
            ->where('date', $todayStr)
            ->value('status');

        $attachments = DB::table('challenge_attachments')
            ->where('challenge_id', $challengeId)
            ->orderByDesc('id')
            ->get();

        $comments = DB::table('challenge_comments as cc')
            ->join('users as u', 'u.id', '=', 'cc.user_id')
            ->where('cc.challenge_id', $challengeId)
            ->orderByDesc('cc.id')
            ->select([
                'cc.id',
                'cc.challenge_id',
                'cc.user_id',
                'cc.content',
                'cc.created_at',
                'u.name as user_name',
            ])
            ->get();

        return [
            'challenge'            => $challenge,
            'participants'         => $rows,
            'myTodayIsDone'        => ($myTodayStatus === 'done'),
            'today'                => $todayStr,

            
            'can_toggle_today'     => $canToggleToday,
            'is_expired'           => $isExpired,
            'my_participant_status'=> $myParticipantStatus,

            'attachments'          => $attachments,
            'comments'             => $comments,
        ];
    }

    //klikniecie done today
    public function toggleToday(int $challengeId): void
    {
        DB::transaction(function () use ($challengeId) {

            $userId = Auth::id();

            // sprawdzamy czy user jest active
            $isMember = DB::table('challenge_participants')
                ->where('challenge_id', $challengeId)
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->exists();//zwraca true false

            abort_if(!$isMember, 403, 'Not allowed');
            //pobieramy callenge i zakres dat
            $challenge = DB::table('challenges')
                ->select('id', 'start_date', 'end_date')
                ->where('id', $challengeId)
                ->first();

            abort_if(!$challenge, 404, 'Challenge not found');
            //pobieramy daty
            $today = Carbon::today()->toDateString();
            $start = Carbon::parse($challenge->start_date)->toDateString();
            $end   = Carbon::parse($challenge->end_date)->toDateString();
            //jezeli poza nie wolno toogle
            abort_if($today < $start || $today > $end, 403, 'Today is out of range');
            //pobranie rekordu dzisiejszego dnia 
            $day = DB::table('challenge_days')
                ->where('challenge_id', $challengeId)
                ->where('user_id', $userId)
                ->where('date', $today)
                ->lockForUpdate()//blokujemy tranzakcje 
                ->first();
            //jesli nie istniej , tworzy 
            if (!$day) {
                DB::table('challenge_days')->insert([
                    'challenge_id' => $challengeId,
                    'user_id'      => $userId,
                    'date'         => $today,
                    'status'       => 'done',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                //przyznanie pkt za done 
                $this->pointService->awardOncePerDay(
                    $userId,
                    PointService::ACTION_DONE_TODAY,
                    5,
                    'challenge',
                    $challengeId,
                    Carbon::parse($today)->startOfDay()
                );

                return;
            }
           // jesli istnieje przelacz status 
            $newStatus = $day->status === 'done' ? 'pending' : 'done';

            DB::table('challenge_days')
                ->where('id', $day->id)
                ->update([
                    'status'     => $newStatus,
                    'updated_at' => now(),
                ]);

            if ($newStatus === 'done') {
                $this->pointService->awardOncePerDay(
                    $userId,
                    PointService::ACTION_DONE_TODAY,
                    5,
                    'challenge',
                    $challengeId,
                    Carbon::parse($today)->startOfDay()
                );
            }
        });
    }
}
