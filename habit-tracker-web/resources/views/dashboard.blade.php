<x-app-layout>

    {{-- LEWA / PRAWA KOLUMNA --}}
    <div class="row g-4">

        {{-- LEWA STRONA – MOJA STREFA --}}
        <div class="col-lg-6 text-white">

            {{-- Powitanie + robot-przewodnik --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="fw-bold mb-1">
                        Cześć, {{ Auth::user()->name ?? 'Użytkowniku' }} 
                    </h1>
                    <p class="opacity-75 mb-0">
                        Oto Twój dzisiejszy przegląd nawyków w Habit Tracker 2.0.
                    </p>
                </div>

                <div class="ms-3">
                    <img src="{{ asset('images/robot.png') }}"
                         alt="Twój przewodnik Habit Tracker"
                         class="guide-robot"
                         style="height: 110px; width: auto;">
                </div>
            </div>

            {{-- KAFELKI: MOJE CELE / DODAJ / WSTRZYMANE / HISTORIA --}}
            <div class="row g-3 mb-4">

                {{-- Moje cele --}}
                <div class="col-md-6 col-sm-6">
                    <a href="{{ route('goals.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0"
                             style="background: rgba(255,255,255,0.08); backdrop-filter: blur(8px);">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check2-square me-2" style="font-size: 1.4rem;"></i>
                                    <h5 class="card-title mb-0 text-white">Moje cele</h5>
                                </div>
                                <p class="card-text text-white-50 mb-0">
                                    Przeglądaj i zarządzaj swoimi celami.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Nowy cel --}}
                <div class="col-md-6 col-sm-6">
                    <a href="{{ route('goals.create') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0"
                             style="background: rgba(255,255,255,0.08); backdrop-filter: blur(8px);">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-plus-circle me-2" style="font-size: 1.4rem;"></i>
                                    <h5 class="card-title mb-0 text-white">Nowy cel</h5>
                                </div>
                                <p class="card-text text-white-50 mb-0">
                                    Dodaj nowy nawyk lub zadanie do śledzenia.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Wstrzymane cele --}}
                <div class="col-md-6 col-sm-6">
                    <a href="{{ route('goals.paused') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0"
                             style="background: rgba(255,255,255,0.08); backdrop-filter: blur(8px);">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-pause-circle me-2" style="font-size: 1.4rem;"></i>
                                    <h5 class="card-title mb-0 text-white">Wstrzymane cele</h5>
                                </div>
                                <p class="card-text text-white-50 mb-0">
                                    Zobacz cele, które chwilowo zatrzymałeś.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Historia --}}
                <div class="col-md-6 col-sm-6">
                    <a href="{{ route('goals.history') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0"
                             style="background: rgba(255,255,255,0.08); backdrop-filter: blur(8px);">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-clock-history me-2" style="font-size: 1.4rem;"></i>
                                    <h5 class="card-title mb-0 text-white">Historia</h5>
                                </div>
                                <p class="card-text text-white-50 mb-0">
                                    Sprawdź swoje wyniki z poprzednich dni.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            {{-- Ostatnie aktywne cele – placeholder (później dane z GoalService) --}}
           <div class="card shadow-sm border-0"
     style="background: rgba(15,23,42,0.85); backdrop-filter: blur(10px);">
    <div class="card-body">
        <h5 class="card-title mb-3 text-white">Twoje aktywne cele</h5>

        @if($activeGoals->isEmpty())
            <p class="text-white-50 mb-0">
                Nie masz jeszcze aktywnych celów. Zacznij od dodania nowego 🙂
            </p>
        @else
            @foreach($activeGoals as $goal)
                @php
                    $percent = $goal->progress_percent;
                @endphp

                <div class="mb-3 pb-3 border-bottom border-secondary">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <div class="fw-semibold text-white">
                                {{ $goal->title }}
                            </div>
                            @if($goal->category)
                                <span class="badge"
                                      style="background:{{ $goal->category->color ?? '#64748b' }};border-radius:999px;">
                                    {{ $goal->category->name }}
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('goals.show', $goal->id) }}"
                           class="btn btn-sm btn-outline-light">
                            Szczegóły
                        </a>
                    </div>

                    <div class="d-flex justify-content-between small text-white-50 mb-1">
                        <span>Postęp</span>
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
            @endforeach
        @endif
    </div>
</div>


        </div>

        {{-- PRAWA STRONA – SPOŁECZNOŚĆ / PUBLICZNE CELE --}}
        <div class="col-lg-6 text-white">

            {{-- Nagłówek sekcji --}}
            <div class="mb-4">
                <h2 class="fw-semibold mb-1">Społeczność i inspiracje</h2>
                <p class="opacity-75 mb-0">
                    Odkrywaj publiczne cele innych użytkowników i szukaj inspiracji.
                </p>
            </div>

            {{-- Kafelki: PUBLICZNE CELE / ZNAJOMI / INSPIRACJE --}}
            <div class="row g-3 mb-4">

                <div class="col-sm-6">
                    <a href="{{ route('goals.public') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0"
                             style="background: rgba(255,255,255,0.08); backdrop-filter: blur(8px);">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-globe2 me-2" style="font-size: 1.4rem;"></i>
                                    <h5 class="card-title mb-0 text-white">Publiczne cele</h5>
                                </div>
                                <p class="card-text text-white-50 mb-0">
                                    Zobacz, nad czym pracują inni użytkownicy.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Zaproszenia do wspólnych celów --}}
<div class="col-md-6 col-sm-6">
    <a href="{{ route('challenges.invites') }}" class="text-decoration-none">
        <div class="card h-100 shadow-sm border-0"
             style="background: rgba(124,58,237,0.15); backdrop-filter: blur(8px);">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope-exclamation me-2"
                       style="font-size: 1.4rem;"></i>

                    <h5 class="card-title mb-0 text-white">
                        Zaproszenia
                    </h5>
                </div>

                <p class="card-text text-white-50 mb-0">
                    Wspólne cele od znajomych
                </p>

                @if(isset($challengeInvitesCount) && $challengeInvitesCount > 0)
                    <span class="badge bg-danger mt-2">
                        {{ $challengeInvitesCount }} nowe
                    </span>
                @endif
            </div>
        </div>
    </a>
</div>

{{-- Moje wspólne cele --}}
<div class="col-md-6 col-sm-6">
    <a href="{{ route('challenges.index') }}" class="text-decoration-none">
        <div class="card h-100 shadow-sm border-0"
             style="background: rgba(34,197,94,0.12); backdrop-filter: blur(8px);">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-people-fill me-2" style="font-size: 1.4rem;"></i>
                    <h5 class="card-title mb-0 text-white">Moje wspólne cele</h5>
                </div>

                <p class="card-text text-white-50 mb-0">
                    Lista challenge, w których uczestniczysz
                </p>

                <div class="text-white-50 small mt-2">
                    Wejdź i zobacz szczegóły
                </div>
            </div>
        </div>
    </a>
</div>



                <div class="col-sm-6">
                    <a href="{{ route('friends.index') }}" class="text-decoration-none">

                        <div class="card h-100 shadow-sm border-0"
                             style="background: rgba(255,255,255,0.08); backdrop-filter: blur(8px);">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-people me-2" style="font-size: 1.4rem;"></i>
                                    <h5 class="card-title mb-0 text-white">Znajomi</h5>
                                </div>
                                <p class="card-text text-white-50 mb-0">
                                    Znajomi
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0"
                             style="background: rgba(255,255,255,0.08); backdrop-filter: blur(8px);">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-lightbulb me-2" style="font-size: 1.4rem;"></i>
                                    <h5 class="card-title mb-0 text-white">Inspiracje</h5>
                                </div>
                                <p class="card-text text-white-50 mb-0">
                                    Najczesciej wybierane.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            {{-- Lista publicznych celów – placeholder --}}
            <div class="card shadow-sm border-0"
     style="background: rgba(15,23,42,0.85); backdrop-filter: blur(10px);">
    <div class="card-body">
        <h5 class="card-title mb-3 text-white">Ostatnio aktywne publiczne cele</h5>

        @if($publicGoals->isEmpty())
            <p class="text-white-50 mb-0">
                Na razie brak publicznych celów innych użytkowników.
            </p>
        @else
            @foreach($publicGoals as $goal)
                @php
                    $percent = $goal->progress_percent;
                @endphp

                <div class="mb-3 pb-3 border-bottom border-secondary">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <div class="fw-semibold text-white">
                                {{ $goal->title }}
                            </div>

                            <div class="small text-white-50">
                                Autor:
<a class="text-info text-decoration-none"
   href="{{ route('users.show', $goal->user->id) }}">
    {{ $goal->user->name ?? 'Użytkownik' }}
</a>

                            </div>

                            @if($goal->category)
                                <span class="badge mt-1"
                                      style="background:{{ $goal->category->color ?? '#64748b' }};border-radius:999px;">
                                    {{ $goal->category->name }}
                                </span>
                            @endif
                        </div>

                       <div class="d-flex gap-2 align-items-center">
    @if($goal->is_public)
        <x-goal-like-button :goal="$goal" :liked-goal-ids="$likedGoalIds" />
    @endif

    <a href="{{ route('goals.public.show', $goal->id) }}"
       class="btn btn-sm btn-outline-light">
        Zobacz cel
    </a>
</div>

                    </div>

                    <div class="d-flex justify-content-between small text-white-50 mb-1">
                        <span>Postęp</span>
                        <span>{{ $percent }}%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info"
                             role="progressbar"
                             style="width: {{ $percent }}%;"
                             aria-valuenow="{{ $percent }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>


        </div>

    </div>

</x-app-layout>
