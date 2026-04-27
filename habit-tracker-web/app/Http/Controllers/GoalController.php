<?php

namespace App\Http\Controllers;

use App\Models\GoalCategory;
use App\Models\GoalDay;
use App\Services\GoalService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\ChallengeService;
use App\Services\GoalDayService;



class GoalController extends Controller
{
    public function __construct(
        private GoalService $service,
        private ChallengeService $challengeService,
        private GoalDayService $goalDayService
    ) {}





    // Lista celów + filtrowanie
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category_id', 'status']);

        $goals      = $this->service->getFilteredGoals($filters);
        $categories = GoalCategory::all();
        $today      = Carbon::today();

        $myPoints = (int) DB::table('rankings')
            ->where('user_id', Auth::id())
            ->value('total_points');   // jak brak rekordu -> null

        $myPoints = $myPoints ?: 0;

        return view('goals.index', [
            'goals'      => $goals,
            'categories' => $categories,
            'title'      => 'Twoje cele',
            'today'      => $today,
            'myPoints'   => $myPoints,
        ]);
    }

    // Formularz tworzenia
    public function create()
    {
        $categories = GoalCategory::query()
            ->whereNull('user_id')
            ->orWhere('user_id', Auth::id())
            ->get();

        $myId = Auth::id();

        // znajomi: accepted w obie strony
        $friendIds = DB::table('friendships')
            ->selectRaw("CASE WHEN user_id = ? THEN friend_id ELSE user_id END as friend_id", [$myId])
            ->where('status', 'accepted')
            ->where(function ($q) use ($myId) {
                $q->where('user_id', $myId)
                    ->orWhere('friend_id', $myId);
            })
            ->pluck('friend_id')
            ->unique()
            ->values()
            ->all();

        $friends = empty($friendIds)
            ? collect()
            : DB::table('users')
            ->whereIn('id', $friendIds)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('goals.create', [
            'title'      => 'Dodaj cel',
            'categories' => $categories,
            'friends'    => $friends,
        ]);
    }


    // Zapis nowego celu
    public function store(Request $request)
    {
        if ($request->boolean('is_shared')) {
            $challengeId = $this->challengeService->create($request);

            return redirect()
                ->route('challenges.show', $challengeId)
                ->with('success', 'Wspólny cel został utworzony! Zaproszenia wysłane.');
        }

        $this->service->create($request);

        return redirect()
            ->route('goals.index')
            ->with('success', 'Cel został dodany!');
    }


    // Podgląd celu 
    public function show(int $id)
    {
        $today = Carbon::today();
        $goal  = $this->service->getWithDays($id);

        return view('goals.show', [
            'goal'  => $goal,
            'today' => $today,
            ...$this->service->prepareForShow($goal, $today),
        ]);
    }

    // Formularz edycji
    public function edit(int $id)
    {
        $goal = $this->service->getById($id);

        $categories = GoalCategory::query()
            ->whereNull('user_id')
            ->orWhere('user_id', Auth::id())
            ->get();

        return view('goals.edit', [
            'goal'       => $goal,
            'categories' => $categories,
            'title'      => 'Edytuj cel',
        ]);
    }

    // Zapis edycji
    public function update(Request $request, int $id)
    {
        $this->service->update($request, $id);

        return redirect()
            ->route('goals.index')
            ->with('success', 'Cel został zaktualizowany.');
    }


    // paused
    public function delete(int $id)
    {
        $this->service->deactivate($id);

        return redirect()
            ->route('goals.paused')
            ->with('success', 'Cel został wstrzymany i przeniesiony do listy wstrzymanych.');
    }

    // Lista wstrzymanych celów
    public function paused()
    {
        $goals = $this->service->getPausedGoals();

        return view('goals.paused', [
            'goals' => $goals,
            'title' => 'Wstrzymane cele',
        ]);
    }

    // Wznowienie celu
    public function resume(int $id)
    {
        $this->service->resume($id);

        return redirect()
            ->route('goals.index')
            ->with('success', 'Cel został wznowiony.');
    }

    // Trwałe usunięcie celu
    public function destroy(int $id)
    {
        $this->service->delete($id);

        return redirect()
            ->route('goals.index')
            ->with('success', 'Cel został trwale usunięty.');
    }



    //Wykonanie celu + notatki



    public function markCompletedToday(int $id)
    {
        $this->goalDayService->markDoneForDate($id, now()->toDateString());

        return redirect()->route('goals.noteToday', $id);
    }


    public function noteToday(int $id)
    {
        $goal = $this->service->getById($id);
        $day  = $this->goalDayService->getDayForDate($id, now()->toDateString());

        return view('goals.addNote', compact('goal', 'day'));
    }


    public function saveNote(Request $request, int $id)
    {
        $this->goalDayService->saveNoteForToday($id, $request);

        return redirect()
            ->route('goals.index')
            ->with('success', 'Notatka została zapisana.');
    }


    public function clearNoteToday(int $id)
    {
        $day = $this->goalDayService->getDayForDate($id, now()->toDateString());
        $this->goalDayService->clearNoteForDayId($day->id);

        return redirect()
            ->route('goals.index')
            ->with('success', 'Notatka z dzisiaj została usunięta.');
    }


    // Notatka do konkretnego dnia celu
    public function editNote(int $goal, int $day)
    {
        $goalModel = $this->service->getWithDays($goal);

        /** @var GoalDay|null $dayModel */
        $dayModel = $goalModel->days->firstWhere('id', $day);

        abort_if(!$dayModel, 404);

        return view('goals.addNote', [
            'goal' => $goalModel,
            'day'  => $dayModel,
        ]);
    }

    public function updateNote(Request $request, int $goal, int $day)
    {
        $this->goalDayService->setNoteForDayId(
            $day,
            $request->input('note'),
            $request->file('attachment')
        );


        return redirect()
            ->route('goals.show', $goal)
            ->with('success', 'Notatka została zaktualizowana.');
    }

    public function deleteNote(int $goal, int $day)
    {
        $this->goalDayService->clearNoteForDayId($day);


        return redirect()
            ->route('goals.show', $goal)
            ->with('success', 'Notatka została usunięta.');
    }

    //historia

    public function history()
    {
        $days = $this->service->getExecutionHistory();

        return view('goals.history', compact('days'));
    }



    // Lista publicznych celów
    public function publicGoals()
    {
        $goals = $this->service->getPublicGoals();

        return view('goals.public', [
            'goals' => $goals,
            'title' => 'Publiczne cele',
        ]);
    }

    // Podgląd jednego publicznego celu
    public function showPublic(int $id)
    {
        $goal  = $this->service->getPublicGoalById($id);
        $today = Carbon::today();

        // Jeśli trasa jest publiczna, Auth::user() może być null.
        // Jeśli trasa jest w middleware auth — będzie zawsze User.
        /** @var \App\Models\User|null $me */
        $me = Auth::user();


        $areFriends = $me
            ? $me->isFriendsWith((int) $goal->user_id)
            : false;

        $canComment = $me && ($areFriends || $me->id === (int) $goal->user_id);

        return view('goals.public_show', [
            'goal'       => $goal,
            'today'      => $today,
            'areFriends' => $areFriends,
            'canComment' => $canComment,
        ]);
    }
}
