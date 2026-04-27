<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $users = User::query()
            ->with(['roles:id,name'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('email', 'like', "%{$q}%")
                       ->orWhere('name', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q'));
    }

    public function show(User $user)
    {
        $user->load(['roles:id,name']);

        // Statystyki (zakładam, że masz relację goals() w User)
        $goalsTotal = $user->goals()->count();
        $publicGoalsTotal = $user->goals()->where('is_public', true)->count();

        // Zgłoszenia PRO usera (ostatnie 10)
        $proRequests = ProRequest::query()
            ->with(['goal:id,title'])
            ->where('user_id', $user->id)
            ->orderByDesc('requested_at')
            ->limit(10)
            ->get();

        return view('admin.users.show', compact(
            'user',
            'goalsTotal',
            'publicGoalsTotal',
            'proRequests'
        ));
    }
}