<x-app-layout>
    <div class="container py-4 text-white">

        {{-- HEADER --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h1 class="fw-bold mb-1">Panel Admina</h1>
                <div class="text-white-50 small">
                    Zalogowano jako: {{ auth()->user()->email }}
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                    Powrót do aplikacji
                </a>
                <a href="{{ route('admin.pro_requests.index') }}" class="btn btn-primary btn-sm">
                    ⭐ Kolejka PRO ({{ $pendingProCount }})
                </a>
            </div>
        </div>

        <div class="row g-3">

            {{-- LEWA KOLUMNA --}}
            <div class="col-12 col-lg-5">
                <div class="row g-3">

                    {{-- KAFEL: PRO PENDING --}}
                    <div class="col-12">
                        <div class="card border-0 shadow-lg"
                            style="background:rgba(15,23,42,0.92);backdrop-filter:blur(10px);">
                            <div class="card-body">
                                <div class="text-white-50 small">PRO Requests</div>

                                <div class="d-flex align-items-end justify-content-between">
                                    <div class="fs-2 fw-bold text-white">{{ $pendingProCount }}</div>
                                    <span class="badge bg-warning text-dark">PENDING</span>
                                </div>

                                <div class="text-white small mt-1" style="opacity:.85;">
                                    Najważniejsze na dziś: rozpatrz zgłoszenia.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3 MAŁE KAFLE: Users / Goals / Public --}}
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-12 col-sm-4">
                                <div class="card border-0 shadow-lg h-100"
                                    style="background:rgba(15,23,42,0.78);backdrop-filter:blur(10px);border:1px solid rgba(148,163,184,0.22);">
                                    <div class="card-body">
                                        <div class="small text-white" style="opacity:.85;">Użytkownicy</div>
                                        <div class="fs-3 fw-bold text-white">{{ $usersTotal }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="card border-0 shadow-lg h-100"
                                    style="background:rgba(15,23,42,0.78);backdrop-filter:blur(10px);border:1px solid rgba(148,163,184,0.22);">
                                    <div class="card-body">
                                        <div class="small text-white" style="opacity:.85;">Cele</div>
                                        <div class="fs-3 fw-bold text-white">{{ $goalsTotal }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="card border-0 shadow-lg h-100"
                                    style="background:rgba(15,23,42,0.78);backdrop-filter:blur(10px);border:1px solid rgba(148,163,184,0.22);">
                                    <div class="card-body">
                                        <div class="small text-white" style="opacity:.85;">Publiczne</div>
                                        <div class="fs-3 fw-bold text-white">{{ $publicGoalsTotal }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SZYBKIE AKCJE --}}
                    <div class="col-12">
                        <div class="card border-0 shadow-lg"
                            style="background:rgba(15,23,42,0.78);backdrop-filter:blur(10px);border:1px solid rgba(148,163,184,0.22);">
                            <div class="card-body">
                                <div class="fw-bold text-white mb-2">Szybkie akcje</div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.pro_requests.index') }}"
                                        class="btn btn-outline-light btn-sm">
                                        Przejdź do kolejki PRO
                                    </a>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light btn-sm">
                                        Użytkownicy (lista)
                                    </a>
                                </div>

                                <div class="small text-white mt-2" style="opacity:.75;">
                                    Szybki dostęp do najczęstszych zadań admina.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- PRAWA KOLUMNA --}}
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-lg h-100"
                    style="background:rgba(15,23,42,0.92);backdrop-filter:blur(10px);">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fw-bold text-white">Ostatnie zgłoszenia PRO (wszystkie)</div>
                            <a href="{{ route('admin.pro_requests.index') }}" class="btn btn-outline-light btn-sm">
                                Zobacz wszystkie
                            </a>
                        </div>

                        @if ($latestProRequests->isEmpty())
                            <div class="alert alert-secondary mb-0">Brak zgłoszeń pending.</div>
                        @else
                            <div class="table-responsive">
    <table class="table table-sm table-borderless mb-0 align-middle text-white"
           style="--bs-table-bg: transparent;">
        <thead style="opacity:.85;">
            <tr>
                <th class="text-white">Cel</th>
                <th class="text-white">User</th>
                <th class="text-white">Data</th>
                <th class="text-white text-end">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($latestProRequests as $r)
                <tr style="border-top:1px solid rgba(148,163,184,0.18);">
                    <td class="fw-semibold text-white">{{ $r->goal->title ?? '-' }}</td>
                    <td class="text-white" style="opacity:.9;">{{ $r->user->email ?? '-' }}</td>
                    <td class="text-white" style="opacity:.75;">{{ $r->requested_at?->format('d.m.Y H:i') }}</td>

                    <td class="text-end">
                        @php
                            $status = $r->status;
                            $badgeClass = match($status) {
                                \App\Models\ProRequest::STATUS_PENDING  => 'bg-warning text-dark',
                                \App\Models\ProRequest::STATUS_APPROVED => 'bg-success',
                                \App\Models\ProRequest::STATUS_REJECTED => 'bg-danger',
                                default => 'bg-secondary',
                            };
                        @endphp

                        <span class="badge {{ $badgeClass }}">
                            {{ strtoupper($status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

                            <div class="small text-white mt-2" style="opacity:.75;">
                                Pokazuję ostatnie 5 zgłoszeń.
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
