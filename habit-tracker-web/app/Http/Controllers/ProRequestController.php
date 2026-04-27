<?php

namespace App\Http\Controllers;

use App\Models\ProRequest;
use App\Services\ProRequestService;
use Illuminate\Support\Facades\Auth;

class ProRequestController extends Controller
{
    public function __construct(private ProRequestService $service) {}

    // user tworzy zgłoszenie pro
    public function store(int $id)
    {
        // musiyc zalogowany
        abort_unless(Auth::check(), 403);

        $this->service->createForGoal($id);

        return back()->with('success', 'Zgłoszenie PRO wysłane ✅');
    }

    // podgląd 
    public function show(ProRequest $proRequest)
    {
        

        $proRequest->load([
            'user',
            'goal.category',
            'goal.user',
            'goal.days.attachments',
            'goal.comments.user',
        ]);

        return view('admin.pro_requests.show', compact('proRequest'));
    }
}