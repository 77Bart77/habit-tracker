<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoalCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalCategoryController extends Controller
{
   public function index()
{
    $categories = GoalCategory::query()
        ->whereNull('user_id')              // ✅ globalne
        ->orWhere('user_id', Auth::id())    // ✅ prywatne usera
        ->orderBy('name')
        ->get();

    return response()->json($categories);
}


    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'], // np. "#ff00aa" albo "red"
        ]);

        $cat = GoalCategory::create([
            'user_id' => Auth::id(),
            'name'    => $data['name'],
            'color'   => $data['color'] ?? null,
        ]);

        return response()->json($cat, 201);
    }

    public function show(GoalCategory $goalCategory)
    {
        $this->authorizeOwner($goalCategory);

        return response()->json($goalCategory);
    }

    public function update(Request $request, GoalCategory $goalCategory)
    {
        $this->authorizeOwner($goalCategory);

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $goalCategory->update($data);

        return response()->json($goalCategory);
    }

    public function destroy(GoalCategory $goalCategory)
    {
        $this->authorizeOwner($goalCategory);

        $goalCategory->delete();

        return response()->json(['ok' => true]);
    }

    private function authorizeOwner(GoalCategory $cat): void
    {
        abort_if((int)$cat->user_id !== (int)Auth::id(), 403, 'To nie jest Twoja kategoria.');
    }
}
