<?php

namespace App\Http\Controllers;

use App\Services\ChallengeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChallengeController extends Controller
{
    public function __construct(
        private ChallengeService $service
    ) {}

    public function index()
    {
        $challenges = $this->service->getMyChallenges();

        return view('challenges.index', compact('challenges'));
    }

    public function create()
    {
        return view('challenges.create');
    }

    public function store(Request $request)
    {
        $challengeId = $this->service->create($request);

        return redirect()->route('challenges.show', $challengeId)
            ->with('success', 'Wspólny cel utworzony. Zaproszenia wysłane.');
    }

    public function invites()
    {
        $invites = $this->service->getMyInvites();

        return view('challenges.invites', compact('invites'));
    }

    public function acceptInvite(int $inviteId)
    {
        $challengeId = $this->service->acceptInvite($inviteId);

        return redirect()->route('challenges.show', $challengeId)
            ->with('success', 'Zaproszenie zaakceptowane.');
    }

    public function declineInvite(int $inviteId)
    {
        $this->service->declineInvite($inviteId);

        return redirect()->route('challenges.invites')
            ->with('success', 'Zaproszenie odrzucone.');
    }

    public function show(int $id)
    {
        $data = $this->service->getChallengeDetails($id);

        return view('challenges.show', $data);
    }

    /**
     * Toggle "done today" tylko jeśli challenge jest w zakresie dat
     * i moje uczestnictwo jest nadal active.
     */
    public function toggleToday(int $id)
    {
        // bierzemy dane do UI i jednocześnie walidujemy dostęp (serwis robi abort_if)
        $data = $this->service->getChallengeDetails($id);

        // poza zakresem dat -> nie wywołuj serwisu (żeby nie waliło 403)
        if (!($data['can_toggle_today'] ?? false)) {
            return redirect()->route('challenges.show', $id)
                ->with('success', 'To wyzwanie jest poza zakresem dat — nie można już odhaczać dni.');
        }

        // jeśli uczestnictwo nie jest active (np. finished) -> blokada
        if (($data['my_participant_status'] ?? null) !== 'active') {
            return redirect()->route('challenges.show', $id)
                ->with('success', 'Twoje uczestnictwo jest zakończone — nie możesz już odhaczać dni.');
        }

        $this->service->toggleToday($id);

        return redirect()->route('challenges.show', $id)
            ->with('success', 'Zapisano status na dziś.');
    }

    /**
     * Dodawanie komentarza — tylko dla aktywnych uczestników (status = active).
     * (Zostawiamy tak jak chciałeś: po zakończeniu challenge nie można komentować.)
     */
    public function storeComment(int $challengeId, Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $isMember = DB::table('challenge_participants')
            ->where('challenge_id', $challengeId)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->exists();

        abort_if(!$isMember, 403);

        DB::table('challenge_comments')->insert([
            'challenge_id' => $challengeId,
            'user_id'      => Auth::id(),
            'content'      => $data['content'],
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return back()->with('success', 'Dodano komentarz.');
    }

    /**
     * Usuwanie komentarza — autor komentarza lub organizator wyzwania.
     */
    public function destroyComment(int $commentId)
    {
        $comment = DB::table('challenge_comments')
            ->where('id', $commentId)
            ->first();

        abort_if(!$comment, 404);

        $challenge = DB::table('challenges')
            ->where('id', $comment->challenge_id)
            ->first();

        abort_if(!$challenge, 404);

        $canDelete =
            Auth::id() === (int) $comment->user_id ||
            Auth::id() === (int) $challenge->created_by;

        abort_if(!$canDelete, 403);

        DB::table('challenge_comments')
            ->where('id', $commentId)
            ->delete();

        return back()->with('success', 'Usunięto komentarz.');
    }
}
