<x-app-layout>

    @php
        // helper do ładnej etykiety interwału
        $intervalLabel = function (?int $days) {
            $days = $days ?: 1;

            return match($days) {
                1  => 'Codziennie',
                2  => 'Co 2 dni',
                3  => 'Co 3 dni',
                7  => 'Raz w tygodniu',
                14 => 'Co 2 tygodnie',
                30 => 'Raz w miesiącu',
                default => 'Co '.$days.' dni',
            };
        };

        // helper do statusu celu
        $statusData = function(string $status) {
            return match($status) {
                \App\Models\Goal::STATUS_ACTIVE   => ['Aktywny', 'success'],
                \App\Models\Goal::STATUS_PAUSED   => ['Wstrzymany', 'secondary'],
                \App\Models\Goal::STATUS_FINISHED => ['Zakończony', 'info'],
                default                           => ['Inny', 'dark'],
            };
        };
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4 text-white">
    <div>
        <h1 class="fw-bold mb-1">Twoje cele</h1>
        <p class="opacity-75 mb-0">
            Lista Twoich aktywnych i zakończonych celów. Wstrzymane znajdziesz w osobnej zakładce.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-warning text-dark px-3 py-2">
            ⭐ {{ $myPoints }} pkt
        </span>

        <a href="{{ route('goals.create') }}" class="btn text-white"
           style="background:linear-gradient(90deg,#7c3aed,#3b82f6);border-radius:999px;padding-inline:20px;">
            <i class="bi bi-plus-circle me-1"></i> Nowy cel
        </a>
    </div>
</div>


    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($goals->isEmpty())
        <div class="alert alert-info">
            Nie masz jeszcze żadnych celów. Kliknij
            <strong>„Nowy cel”</strong>, żeby zacząć.
        </div>
    @else
        <div class="row g-4">
            @foreach($goals as $goal)
                @php
                    [$statusLabel, $statusColor] = $statusData($goal->status);

                    // miniaturka – pierwszy załącznik
                    $thumb = $goal->attachments->first();

                    // 🔹 dzisiejszy rekord dnia (jeśli istnieje)
                    $todayDay = $goal->days->first(function ($day) use ($today) {
                        return $day->date->isSameDay($today);
                    });

                    $todayDone = $todayDay && $todayDay->status === \App\Models\GoalDay::STATUS_DONE;

                    // 🔹 procent wykonania celu (akcesor z modelu Goal)
    $percent = $goal->progress_percent;
                @endphp

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0"
                         style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                        <div class="card-body text-white d-flex flex-column">

                            {{-- MINIATURKA --}}
                            @if($thumb)
                                <div class="mb-3">
                                    @if(Str::startsWith($thumb->mime_type, 'image/'))
                                        <img src="{{ asset('storage/'.$thumb->file_path) }}"
                                             alt="Załącznik"
                                             style="width:220px;height:220px;object-fit:cover;border-radius:20px;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center"
                                             style="width:220px;height:220px;border-radius:20px;
                                                    background:rgba(15,23,42,0.9);border:1px solid rgba(148,163,184,0.8);">
                                            <i class="bi bi-play-circle" style="font-size:3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Tytuł + status + kategoria --}}
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="mb-1">{{ $goal->title }}</h5>

                                    @if($goal->category)
                                        <span class="badge"
                                              style="background:{{ $goal->category->color ?? '#64748b' }};
                                                     border-radius:999px;">
                                            {{ $goal->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <span class="badge bg-{{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            {{-- Opis --}}
                            <p class="text-white-50 small mb-2">
                                {{ $goal->description ? Str::limit($goal->description, 90) : 'Brak opisu.' }}
                            </p>

                            {{-- Daty + interwał --}}
                            <p class="text-white-50 small mb-3">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $goal->start_date?->format('d.m.Y') }}
                                –
                                {{ $goal->end_date?->format('d.m.Y') }}
                                · {{ $intervalLabel((int)($goal->interval_days ?? 1)) }}
                            </p>
                            {{-- Postęp celu --}}
<div class="mb-3">
    <div class="d-flex justify-content-between small text-white-50 mb-1">
        <span>Postęp celu</span>
        <span>{{ $percent }}%</span>
    </div>

    <div class="progress" style="height: 6px;">
        <div class="progress-bar bg-success"
             role="progressbar"
             style="width: {{ $percent }}%;"
             aria-valuenow="{{ $percent }}"
             aria-valuemin="0"
             aria-valuemax="100">
        </div>
    </div>
</div>
                            <div class="mt-auto d-flex flex-wrap gap-2">

                                {{-- Szczegóły --}}
                                <a href="{{ route('goals.show', $goal->id) }}"
                                   class="btn btn-sm btn-outline-light">
                                    <i class="bi bi-eye me-1"></i> Szczegóły
                                </a>

                                {{-- Edycja --}}
                                @if($goal->status !== \App\Models\Goal::STATUS_FINISHED)
                                    <a href="{{ route('goals.edit', $goal->id) }}"
                                       class="btn btn-sm btn-outline-light">
                                        <i class="bi bi-pencil-square me-1"></i> Edytuj
                                    </a>
                                @endif

                                {{-- Wstrzymaj / Wznów --}}
                                @if($goal->status === \App\Models\Goal::STATUS_ACTIVE)
                                    <form method="POST"
                                          action="{{ route('goals.delete', $goal->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pause-circle me-1"></i> Wstrzymaj
                                        </button>
                                    </form>
                                @elseif($goal->status === \App\Models\Goal::STATUS_PAUSED)
                                    <a href="{{ route('goals.edit', $goal->id) }}"
                                       class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-play-circle me-1"></i> Edytuj i wznów
                                    </a>
                                @endif

                                {{-- Wykonano dziś --}}
                                @if($goal->status === \App\Models\Goal::STATUS_ACTIVE)
                                    @if($todayDay && !$todayDone)
                                        <form method="POST"
                                              action="{{ route('goals.markCompletedToday', $goal->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check2-circle me-1"></i> Wykonano dziś
                                            </button>
                                        </form>
                                    @elseif($todayDone)
                                        <button class="btn btn-sm btn-outline-success" disabled>
                                            <i class="bi bi-check2-circle me-1"></i> Już wykonano dziś
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            Dziś nie jest dniem realizacji
                                        </button>
                                    @endif
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-app-layout>
