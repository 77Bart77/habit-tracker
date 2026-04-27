<x-app-layout>

    @php
        // Label interwału
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

        // Status celu
        $statusData = function(string $status) {
            return match($status) {
                \App\Models\Goal::STATUS_ACTIVE   => ['Aktywny', 'success'],
                \App\Models\Goal::STATUS_PAUSED   => ['Wstrzymany', 'secondary'],
                \App\Models\Goal::STATUS_FINISHED => ['Zakończony', 'info'],
                default                           => ['Inny', 'dark'],
            };
        };

        [$statusLabel, $statusColor] = $statusData($goal->status);

        // procent postępu (akcesor w modelu Goal)
        $percent = $goal->progress_percent;
    @endphp

    <div class="row justify-content-center">
        <div class="col-lg-10 text-white">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div class="d-flex align-items-center gap-3">
    <h1 class="fw-bold mb-1">{{ $goal->title }}</h1>

    <x-goal-like-button
        :goal="$goal"
        :liked-goal-ids="$likedGoalIds"
        :show-count="true"
    />
</div>


                    {{-- Autor --}}
                    <div class="small text-white-50 mb-1">
                        Autor: {{ $goal->user->name ?? 'Użytkownik' }}
                    </div>

                    {{-- Kategoria + status --}}
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @if($goal->category)
                            <span class="badge"
                                  style="background:{{ $goal->category->color ?? '#64748b' }};border-radius:999px;">
                                {{ $goal->category->name }}
                            </span>
                        @endif

                        <span class="badge bg-{{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>

                        <span class="badge bg-info">
                            Publiczny cel
                        </span>
                    </div>
                </div>

                <div class="ms-3 d-none d-md-block">
                    <img src="{{ asset('images/robot.png') }}"
                         alt="Przewodnik Habit Tracker"
                         style="height:70px;width:auto;">
                </div>
            </div>

            {{-- INFO CARD --}}
            <div class="card shadow-lg border-0 mb-4"
                 style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                <div class="card-body">

                    {{-- Opis --}}
                    @if($goal->description)
                        <p class="mb-3 text-white">{{ $goal->description }}</p>
                    @else
                        <p class="mb-3 text-white-50 fst-italic">Autor nie dodał opisu.</p>
                    @endif

                    {{-- Daty --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="small text-white-50">Data rozpoczęcia</div>
                            <div class="text-white">{{ $goal->start_date?->format('d.m.Y') }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="small text-white-50">Data zakończenia</div>
                            <div class="text-white">{{ $goal->end_date?->format('d.m.Y') }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="small text-white-50">Intensywność</div>
                            <div class="text-white">
                                {{ $intervalLabel((int)($goal->interval_days ?? 1)) }}
                            </div>
                        </div>
                    </div>

                    {{-- Podsumowanie postępu --}}
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

                    {{-- Przyciski – tylko nawigacja --}}
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Powrót do dashboardu
                        </a>

                        <a href="{{ route('goals.public') ?? '#' }}" class="btn btn-outline-light btn-sm d-none">
                            {{-- opcjonalnie kiedyś lista wszystkich publicznych celów --}}
                        </a>
                    </div>

                    <p class="mt-3 mb-0 small text-white-50">
                        To jest publiczny podgląd celu. Tylko właściciel może go edytować i odhaczać dni.
                    </p>
                </div>
            </div>

            {{-- OŚ CZASU (tylko do odczytu) --}}
            <div class="card shadow-lg border-0"
                 style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                <div class="card-body">
                    <h5 class="mb-3 text-white">Oś czasu realizacji</h5>

                    @if($goal->days->isEmpty())
                        <p class="text-white-50 mb-0">
                            Autor nie ma jeszcze zaplanowanych dni dla tego celu.
                        </p>
                    @else
                        @foreach($goal->days->sortBy('date') as $day)
                            @php
                                // $day->date to Carbon (przez cast w modelu)
                                $date = $day->date;

                                $isPast   = $date->lt($today);
                                $isToday  = $date->isSameDay($today);
                                $isFuture = $date->gt($today);

                                if ($day->status === \App\Models\GoalDay::STATUS_DONE) {
                                    $dayLabel = 'Wykonano';
                                    $dayClass = 'success';
                                } elseif ($isPast) {
                                    $dayLabel = 'Niezrealizowano';
                                    $dayClass = 'danger';
                                } else {
                                    $dayLabel = 'Oczekuje';
                                    $dayClass = 'secondary';
                                }

                                $formatted = $date->format('d.m.Y');
                            @endphp

                            <div class="d-flex justify-content-between align-items-start py-2 border-bottom border-secondary">
                                <div>
                                    {{-- DATA --}}
                                    <div class="fw-semibold text-white">
                                        {{ $formatted }}
                                        @if($isToday)
                                            <span class="badge bg-light text-dark ms-2">Dzisiaj</span>
                                        @endif
                                    </div>

                                    {{-- NOTATKA (tylko do odczytu) --}}
                                    @if($day->note)
                                        <div class="mt-1 small text-white">
                                            <strong>Notatka autora:</strong> {{ $day->note }}
                                        </div>
                                    @endif
                                </div>

                                <span class="badge bg-{{ $dayClass }}">
                                    {{ $dayLabel }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- ===================== --}}
{{-- KOMENTARZE --}}
{{-- ===================== --}}
<div class="card shadow-lg border-0 mt-4"
     style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
    <div class="card-body text-white">

        <h5 class="mb-3">
            Komentarze ({{ $goal->comments->count() }})
        </h5>

        {{-- LISTA --}}
        @if($goal->comments->isEmpty())
            <p class="text-white-50 small mb-0">
                Brak komentarzy. Bądź pierwszy 🙂
            </p>
        @else
            <div class="d-flex flex-column gap-3">
                @foreach($goal->comments->sortByDesc('created_at') as $comment)
                    <div class="p-3 rounded"
                         style="background:rgba(2,6,23,0.6);">

                        <div class="d-flex justify-content-between align-items-start gap-2">

                            <div>
                                <div class="fw-semibold small">
                                    {{ $comment->user->name ?? 'Użytkownik' }}
                                    <span class="text-white-50 fw-normal ms-2">
                                        {{ $comment->created_at->format('d.m.Y H:i') }}
                                    </span>
                                </div>

                                <div class="mt-2">
                                    {{ $comment->content }}
                                </div>
                            </div>

                            {{-- USUŃ --}}
                            @if(auth()->check() && (
                                auth()->id() === $comment->user_id ||
                                auth()->id() === $goal->user_id
                            ))
                                <form method="POST"
                                      action="{{ route('comments.destroy', $comment->id) }}"
                                      onsubmit="return confirm('Usunąć komentarz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Usuń
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- DODAWANIE --}}
        <div class="mt-4">
            @if($canComment)
                <form method="POST"
                      action="{{ route('goals.comments.store', $goal->id) }}">
                    @csrf

                    <textarea name="content"
                              class="form-control mb-2"
                              rows="3"
                              maxlength="1000"
                              placeholder="Napisz komentarz..."
                              style="background:rgba(15,23,42,0.7);
                                     color:#fff;
                                     border-color:rgba(148,163,184,0.25);"></textarea>

                    <button class="btn btn-primary btn-sm">
                        Dodaj komentarz
                    </button>
                </form>
            @else
                <p class="text-white-50 small mb-0">
                    Komentarze mogą dodawać tylko znajomi autora celu.
                </p>
            @endif
        </div>

    </div>
</div>


        </div>
    </div>

</x-app-layout>
