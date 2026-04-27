<x-app-layout>
    <div class="container py-4 text-white">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="fw-bold mb-0">Zgłoszenia PRO</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">Panel</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">

            {{-- LEWA: PENDING --}}
            <div class="col-12 col-lg-6">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Do rozpatrzenia (PENDING)</div>
                    <span class="badge bg-warning text-dark">{{ $pending->total() }}</span>
                </div>

                @if($pending->count() === 0)
                    <div class="alert alert-secondary mb-0">Brak zgłoszeń do rozpatrzenia.</div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($pending as $req)
                            <div class="card border-0 shadow-lg"
                                 style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <a class="fw-bold text-white text-decoration-none"
   style="cursor:pointer;"
   href="{{ route('admin.pro_requests.show', $req) }}">
    {{ $req->goal->title ?? 'Cel' }}
</a>
                                            <div class="small text-white" style="opacity:.75;">
                                                User: {{ $req->user->email ?? '-' }} |
                                                requested: {{ $req->requested_at?->format('d.m.Y H:i') }}
                                            </div>
                                        </div>

                                        <span class="badge bg-warning text-dark">PENDING</span>
                                    </div>

                                    {{-- APPROVE --}}
                                    <form method="POST"
                                          action="{{ route('admin.pro_requests.approve', $req) }}"
                                          class="mt-3 d-flex gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <input name="admin_note" class="form-control form-control-sm"
                                               placeholder="Notatka admina (opcjonalnie)"
                                               style="background:rgba(15,23,42,0.7);color:#fff;border-color:rgba(148,163,184,0.25);">

                                        <button class="btn btn-success btn-sm">Approve</button>
                                    </form>

                                    {{-- REJECT --}}
                                    <form method="POST"
                                          action="{{ route('admin.pro_requests.reject', $req) }}"
                                          class="mt-2 d-flex gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <input name="admin_note" class="form-control form-control-sm"
                                               placeholder="Powód odrzucenia (opcjonalnie)"
                                               style="background:rgba(15,23,42,0.7);color:#fff;border-color:rgba(148,163,184,0.25);">

                                        <button class="btn btn-outline-danger btn-sm">Reject</button>
                                    </form>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        {{ $pending->links() }}
                    </div>
                @endif
            </div>

            {{-- PRAWA: ROZPATRZONE --}}
            <div class="col-12 col-lg-6">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Rozpatrzone</div>
                    <span class="badge bg-secondary">{{ $reviewed->total() }}</span>
                </div>

                @if($reviewed->count() === 0)
                    <div class="alert alert-secondary mb-0">Brak rozpatrzonych zgłoszeń.</div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($reviewed as $req)
                            @php
                                $badge = match($req->status) {
                                    \App\Models\ProRequest::STATUS_APPROVED => 'bg-success',
                                    \App\Models\ProRequest::STATUS_REJECTED => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp

                            <div class="card border-0 shadow-lg"
                                 style="background:rgba(15,23,42,0.78);backdrop-filter:blur(10px);border:1px solid rgba(148,163,184,0.22);">
                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <a class="fw-bold text-white text-decoration-none"
   style="cursor:pointer;"
   href="{{ route('admin.pro_requests.show', $req) }}">
    {{ $req->goal->title ?? 'Cel' }}
</a>
                                            <div class="small text-white" style="opacity:.75;">
                                                User: {{ $req->user->email ?? '-' }}
                                                {{-- jeśli nie masz reviewed_at -> możesz dać updated_at --}}
                                                @if($req->reviewed_at)
                                                    | reviewed: {{ $req->reviewed_at?->format('d.m.Y H:i') }}
                                                @else
                                                    | updated: {{ $req->updated_at?->format('d.m.Y H:i') }}
                                                @endif
                                            </div>
                                        </div>

                                        <span class="badge {{ $badge }}">
                                            {{ strtoupper($req->status) }}
                                        </span>
                                    </div>

                                    @if(!empty($req->admin_note))
                                        <div class="mt-2 small text-white" style="opacity:.8;">
                                            <span class="text-white-50">Notatka:</span> {{ $req->admin_note }}
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        {{ $reviewed->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>