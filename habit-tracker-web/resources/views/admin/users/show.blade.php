<x-app-layout>
    <div class="container py-4 text-white">

        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h1 class="fw-bold mb-0 text-white">Podgląd użytkownika</h1>
                <div class="text-white-50 small">Szczegóły konta</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light btn-sm">
                    ← Lista użytkowników
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">
                    Panel
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- LEWA KOLUMNA --}}
            <div class="col-12 col-lg-4">

                {{-- USER --}}
                <div class="card border-0 shadow-lg mb-3"
                    style="background:rgba(15,23,42,.95);backdrop-filter:blur(10px);">
                    <div class="card-body">

                        <div class="fw-bold fs-5 text-white mb-1">
                            {{ $user->email }}
                        </div>

                        <div class="text-white-50 small mb-3">
                            ID: {{ $user->id }}
                        </div>

                        <div class="mb-3">
                            <div class="text-white-50 small">Imię</div>
                            <div class="fw-semibold text-white">
                                {{ $user->name ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-white-50 small mb-1">Role</div>
                            <div class="d-flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
                                    @php
                                        $cls = match ($role->name) {
                                            'admin' => 'bg-danger',
                                            'pro' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge rounded-pill {{ $cls }}">
                                        {{ strtoupper($role->name) }}
                                    </span>
                                @empty
                                    <span class="badge rounded-pill bg-secondary">USER</span>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>

                {{-- STATS --}}
                <div class="card border-0 shadow-lg"
                    style="background:rgba(15,23,42,.85);backdrop-filter:blur(10px);border:1px solid rgba(148,163,184,.25);">
                    <div class="card-body">

                        <div class="fw-bold mb-3 text-white">Statystyki</div>

                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-white-50">Cele (łącznie)</span>
                            <span class="fw-semibold text-white">{{ $goalsTotal }}</span>
                        </div>

                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-white-50">Cele publiczne</span>
                            <span class="fw-semibold text-white">{{ $publicGoalsTotal }}</span>
                        </div>

                        <div class="d-flex justify-content-between small">
                            <span class="text-white-50">Zgłoszenia PRO</span>
                            <span class="fw-semibold text-white">{{ $proRequests->count() }}</span>
                        </div>

                    </div>
                </div>

            </div>

            {{-- PRAWA KOLUMNA --}}
            <div class="col-12 col-lg-8">

                <div class="card border-0 shadow-lg h-100"
                    style="background:rgba(15,23,42,.95);backdrop-filter:blur(10px);">
                    <div class="card-body">

                        <div class="fw-bold mb-3 text-white">
                            Ostatnie zgłoszenia PRO
                        </div>

                        @if ($proRequests->isEmpty())
                            <div class="alert alert-secondary mb-0">
                                Brak zgłoszeń PRO.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0 bg-white">
                                    <thead class="border-bottom">
                                        <tr class="small text-muted">
                                            <th>Cel</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-end">Data</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($proRequests as $r)
                                            @php
                                                $badge = match ($r->status) {
                                                    \App\Models\ProRequest::STATUS_APPROVED => 'bg-success',
                                                    \App\Models\ProRequest::STATUS_REJECTED => 'bg-danger',
                                                    default => 'bg-warning text-dark',
                                                };
                                            @endphp

                                            <tr>
                                                <td class="fw-semibold text-dark">
                                                    {{ $r->goal->title ?? '—' }}
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge rounded-pill {{ $badge }}">
                                                        {{ strtoupper($r->status) }}
                                                    </span>
                                                </td>

                                                <td class="text-end text-muted small">
                                                    {{ $r->requested_at?->format('d.m.Y H:i') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
