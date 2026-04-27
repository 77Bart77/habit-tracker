<?php

namespace App\Http\Controllers;

use App\Models\GoalComment;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalCommentController extends Controller
{
    // Metoda do dodania komentarza
    public function store(Request $request, $goalId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $goal = Goal::findOrFail($goalId);

        // Tworzenie nowego komentarza
        $comment = GoalComment::create([
            'goal_id' => $goal->id,
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return back()->with('success', 'Komentarz został dodany!');
    }

    // Metoda do usunięcia komentarza
    public function destroy($commentId)
    {
        $comment = GoalComment::findOrFail($commentId);

        // Sprawdzamy, czy użytkownik jest autorem komentarza
        if ($comment->user_id === Auth::id()) {
            $comment->delete();
            return back()->with('success', 'Komentarz został usunięty!');
        }

        return back()->with('error', 'Nie masz uprawnień do usunięcia tego komentarza.');
    }
}
