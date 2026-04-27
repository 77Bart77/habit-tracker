<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProRequest;
use App\Models\User;
use App\Models\Goal;

class AdminDashboardController extends Controller
{
    public function index()
{
    $pendingProCount = ProRequest::query()
        ->where('status', ProRequest::STATUS_PENDING)
        ->count();

    $latestProRequests = ProRequest::query()
    ->with(['user:id,email', 'goal:id,title'])
    ->orderByDesc('requested_at')
    ->limit(5)
    ->get();

    // nowe statystyki
    $usersTotal = User::query()->count();

    $goalsTotal = Goal::query()->count();

    $publicGoalsTotal = Goal::query()
        ->where('is_public', true)
        ->count();

    return view('admin.dashboard', compact(
        'pendingProCount',
        'latestProRequests',
        'usersTotal',
        'goalsTotal',
        'publicGoalsTotal'
    ));
}
}