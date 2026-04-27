<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProRequest;
use App\Services\ProRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminProRequestController extends Controller
{
    public function __construct(private ProRequestService $service) {}

    public function index()
    {
        $pending = ProRequest::query()
            ->with(['goal:id,title', 'user:id,email'])
            ->where('status', ProRequest::STATUS_PENDING)
            ->orderByDesc('requested_at')
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString(); 

        $reviewedQuery = ProRequest::query()
            ->with(['goal:id,title', 'user:id,email'])
            ->whereIn('status', [ProRequest::STATUS_APPROVED, ProRequest::STATUS_REJECTED]);

        // Sortowanie: jeśli istnieje reviewed_at -> po reviewed_at, w przeciwnym razie po requested_at
        $reviewedQuery->orderByDesc(
            Schema::hasColumn('pro_requests', 'reviewed_at') ? 'reviewed_at' : 'requested_at'
        );

        $reviewed = $reviewedQuery
            ->paginate(10, ['*'], 'reviewed_page')
            ->withQueryString(); // ważne przy 2 paginatorach na 1 stronie

        return view('admin.pro_requests.index', compact('pending', 'reviewed'));
    }

    public function show(ProRequest $proRequest)
    {
        $proRequest->load([
            'user:id,email,name',
            'goal' => function ($q) {
                $q->with([
                    'category',
                    'user:id,email,name',
                    'days.attachments',
                    'comments.user:id,email,name',
                ]);
            },
        ]);

        return view('admin.pro_requests.show', compact('proRequest'));
    }

    public function approve(Request $request, ProRequest $proRequest): RedirectResponse
    {
        $data = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->approve(
            proRequestId: $proRequest->id,
            adminNote: $data['admin_note'] ?? null,
            bonusPoints: 100
        );

        // wracamy na listę i zachowujemy parametry paginacji
        return redirect()->back()->with('success', 'Zgłoszenie zatwierdzone!');
    }

    public function reject(Request $request, ProRequest $proRequest): RedirectResponse
    {
        $data = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->reject(
            proRequestId: $proRequest->id,
            adminNote: $data['admin_note'] ?? null
        );

        return redirect()->back()->with('success', 'Zgłoszenie odrzucone ❌');
    }
}