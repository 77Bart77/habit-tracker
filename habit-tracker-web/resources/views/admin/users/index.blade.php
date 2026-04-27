<x-app-layout>
    <div class="container py-4 text-white">

        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h1 class="fw-bold mb-0">Użytkownicy</h1>
                <div class="text-white-50 small">Lista użytkowników aplikacji</div>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">
                Panel
            </a>
        </div>

        {{-- SEARCH --}}
        <div class="card border-0 shadow-lg mb-3"
            style="background:rgba(15,23,42,0.78);backdrop-filter:blur(10px);border:1px solid rgba(148,163,184,0.22);">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
                    <div class="col-12 col-md">
                        <input type="text" name="q" value="{{ $q }}"
                            class="form-control form-control-sm" placeholder="Szukaj po email lub nazwie…"
                            style="background:rgba(15,23,42,0.70);color:#fff;border-color:rgba(148,163,184,0.25);">
                    </div>

                    <div class="col-12 col-md-auto d-flex gap-2">
                        <button class="btn btn-primary btn-sm">Szukaj</button>

                        @if ($q !== '')
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light btn-sm">
                                Wyczyść
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card border-0 shadow-lg" style="background:rgba(15,23,42,0.92);backdrop-filter:blur(10px);">
            <div class="card-body">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                    <div class="fw-bold">Wyniki</div>
                    <div class="text-white-50 small">Łącznie: {{ $users->total() }}</div>
                </div>

                @if ($users->count() === 0)
                    <div class="alert alert-secondary mb-0">Brak użytkowników do wyświetlenia.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0 align-middle text-white">
                            <thead style="opacity:.85;">
                                <tr>
                                    <th>Użytkownik</th>
                                    <th class="text-nowrap">Role</th>
                                    <th class="text-end">Akcje</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($users as $u)
                                    @php
                                        $roleNames = $u->roles?->pluck('name')->values() ?? collect();
                                    @endphp

                                    <tr style="border-top:1px solid rgba(148,163,184,0.18);">
                                        {{-- USER CELL --}}
                                        <td>
                                            <div class="d-flex flex-column">
                                                <div class="fw-semibold">{{ $u->email }}</div>
                                                <div class="small text-white" style="opacity:.75;">
                                                    {{ $u->name ?? '—' }}
                                                    <span class="mx-1" style="opacity:.35;">•</span>
                                                    ID: {{ $u->id }}
                                                </div>
                                            </div>
                                        </td>

                                        {{-- ROLES --}}
                                        <td class="text-nowrap">
                                            @if ($roleNames->isEmpty())
                                                <span class="badge bg-secondary">user</span>
                                            @else
                                                @foreach ($roleNames as $r)
                                                    @php
                                                        $cls = match ($r) {
                                                            'admin' => 'bg-danger',
                                                            'pro' => 'bg-success',
                                                            default => 'bg-secondary',
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $cls }}">{{ $r }}</span>
                                                @endforeach
                                            @endif
                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-dark btn-sm dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Akcje
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                                    {{-- Krok 1: tylko UI --}}
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.users.show', $u) }}">
                                                            Podgląd
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item disabled" href="#">
                                                            ⭐ PRO: nadaj/odbierz (opcjonalne)
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item disabled" href="#">
                                                            ⛔ Zablokuj/odblokuj (później)
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                @endif

            </div>
        </div>

    </div>
</x-app-layout>
