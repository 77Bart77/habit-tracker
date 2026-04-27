{{-- resources/views/challenges/show.blade.php --}}

<x-app-layout>

    @php
        // moje staty (z participants)
        $meRow = collect($participants)->firstWhere('user_id', auth()->id());
        $myPercent = $meRow ? (int)($meRow['percent'] ?? 0) : 0;
        $myDone    = $meRow ? (int)($meRow['done'] ?? 0) : 0;
        $myTotal   = $meRow ? (int)($meRow['total'] ?? 0) : 0;

        $startFmt = \Illuminate\Support\Carbon::parse($challenge->start_date)->format('d.m.Y');
        $endFmt   = \Illuminate\Support\Carbon::parse($challenge->end_date)->format('d.m.Y');

        // flagi z serwisu (bezpieczne defaulty)
        $canToggleToday       = (bool)($can_toggle_today ?? false);
        $isExpired            = (bool)($is_expired ?? false);
        $myParticipantStatus  = $my_participant_status ?? null; // active/finished/...
    @endphp

    <div class="row justify-content-center">
        <div class="col-lg-10 text-white">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold mb-1">{{ $challenge->title ?? 'Wspólny cel' }}</h1>

                    <span class="badge bg-info">Wspólny cel</span>

                    <span class="badge bg-secondary ms-2">
                        {{ $startFmt }} → {{ $endFmt }}
                    </span>

                    @if($myParticipantStatus && $myParticipantStatus !== 'active')
                        <span class="badge bg-dark ms-2">
                            {{ strtoupper($myParticipantStatus) }}
                        </span>
                    @endif
                </div>

                <div class="ms-3 d-none d-md-block">
                    <img src="{{ asset('images/robot.png') }}"
                         alt="Przewodnik Habit Tracker"
                         style="height:70px;width:auto;">
                </div>
            </div>

            {{-- ALERT --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- INFO CARD --}}
            <div class="card shadow-lg border-0 mb-4"
                 style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                <div class="card-body">

                    {{-- Opis --}}
                    @if(!empty($challenge->description))
                        <p class="mb-3 text-white" style="white-space: pre-wrap;">
                            {{ $challenge->description }}
                        </p>
                    @else
                        <p class="mb-3 text-white-50 fst-italic">Brak opisu.</p>
                    @endif

                    {{-- Daty + moje staty --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="small text-white-50">Data rozpoczęcia</div>
                            <div class="text-white">{{ $startFmt }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="small text-white-50">Data zakończenia</div>
                            <div class="text-white">{{ $endFmt }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="small text-white-50">Moje wykonane dni</div>
                            <div class="text-white">{{ $myDone }} / {{ $myTotal }}</div>
                        </div>
                    </div>

                    {{-- Progress bar (mój progres) --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-white-50 mb-1">
                            <span>Mój postęp</span>
                            <span>{{ $myPercent }}%</span>
                        </div>

                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success"
                                 role="progressbar"
                                 style="width: {{ $myPercent }}%;"
                                 aria-valuenow="{{ $myPercent }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </div>

                    {{-- Przyciski nawigacyjne --}}
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('challenges.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Moje wspólne cele
                        </a>

                        <a href="{{ route('challenges.invites') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-envelope me-1"></i> Zaproszenia
                        </a>
                    </div>

                </div>
            </div>

            {{-- SEKCJA “DZISIAJ” (blokada jak w goal.show) --}}
            <div class="card shadow-lg border-0 mb-4"
                 style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                <div class="card-body">
                    <h5 class="text-white mb-2">Dzisiejszy postęp</h5>

                    <div class="text-white-50 small mb-3">
                        Dzisiaj: {{ $today }}
                    </div>

                    {{-- 1) jeśli moje uczestnictwo nie jest active --}}
                    @if(($myParticipantStatus ?? null) !== 'active')
                        <div class="alert alert-secondary mb-0">
                            Twoje uczestnictwo w tym wspólnym celu jest zakończone — nie możesz już odhaczać dni.
                        </div>

                    {{-- 2) jeśli dziś poza zakresem dat --}}
                    @elseif(!$canToggleToday)
                        @if($isExpired)
                            <div class="alert alert-info mb-0">
                                Wyzwanie jest zakończone — nie można już odhaczać dni.
                            </div>
                        @else
                            <div class="alert alert-secondary mb-0">
                                Dziś nie jest w zakresie dat tego wyzwania.
                            </div>
                        @endif

                    {{-- 3) w zakresie dat + active -> toggle --}}
                    @else
                        <div class="text-white-50 small mb-3">
                            Kliknij, aby oznaczyć swój status na dziś.
                        </div>

                        <form method="POST" action="{{ route('challenges.toggleToday', $challenge->id) }}">
                            @csrf

                            @if($myTodayIsDone)
                                <button class="btn btn-outline-warning w-100" style="border-radius:999px;">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                    Cofnij dzisiaj (ustaw pending)
                                </button>
                            @else
                                <button class="btn btn-success w-100" style="border-radius:999px;">
                                    <i class="bi bi-check2-circle me-1"></i>
                                    Wykonano dziś
                                </button>
                            @endif
                        </form>
                    @endif
                </div>
            </div>

            {{-- ZAŁĄCZNIKI --}}
            @if(isset($attachments) && $attachments->count())
                <div class="card shadow-lg border-0 mb-4"
                     style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                    <div class="card-body">
                        <h5 class="mb-3 text-white">Załączniki</h5>

                        <div class="d-flex flex-wrap gap-3">
                            @foreach($attachments as $a)
                                @php
                                    $url = asset('storage/' . $a->file_path);
                                    $mime = $a->mime_type ?? '';
                                    $isImage = str_starts_with($mime, 'image/');
                                    $isVideo = str_starts_with($mime, 'video/');
                                @endphp

                                <div class="rounded p-2" style="background: rgba(255,255,255,0.06); width: 240px;">
                                    <div class="small text-white-50 mb-2" style="word-break: break-word;">
                                        {{ $a->original_name ?? 'plik' }}
                                    </div>

                                    @if($isImage)
                                        <a href="{{ $url }}" target="_blank" rel="noopener">
                                            <img src="{{ $url }}" class="img-fluid rounded" alt="">
                                        </a>
                                    @elseif($isVideo)
                                        <video src="{{ $url }}" controls class="w-100 rounded"></video>
                                    @else
                                        <a class="btn btn-sm btn-outline-light" href="{{ $url }}" target="_blank" rel="noopener">
                                            Otwórz plik
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            @endif

            {{-- UCZESTNICY I POSTĘP --}}
            <div class="card shadow-lg border-0"
                 style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                <div class="card-body">
                    <h5 class="mb-3 text-white">Uczestnicy i postęp</h5>

                    @foreach($participants as $p)
                        @php
                            $ts = $p['today_status'] ?? null;

                            if ($ts === 'done') {
                                $todayLabel = 'Dziś: DONE';
                                $todayClass = 'success';
                            } elseif ($ts === 'pending') {
                                $todayLabel = 'Dziś: PENDING';
                                $todayClass = 'secondary';
                            } else {
                                $todayLabel = 'Dziś: —';
                                $todayClass = 'dark';
                            }
                        @endphp

                        <div class="d-flex justify-content-between align-items-start py-2 border-bottom border-secondary">
                            <div>
                                <div class="fw-semibold text-white">
                                    {{ $p['name'] }}
                                </div>

                                <div class="text-white-50 small">
                                    Wykonane: {{ $p['done'] }} / {{ $p['total'] }} • {{ $p['percent'] }}%
                                </div>

                                <div class="mt-1">
                                    <span class="badge bg-{{ $todayClass }}">{{ $todayLabel }}</span>
                                </div>
                            </div>

                            <div class="text-end" style="min-width: 160px;">
                                <div class="small text-white-50">Postęp</div>
                                <div class="fw-bold">{{ $p['percent'] }}%</div>

                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-success"
                                         role="progressbar"
                                         style="width: {{ $p['percent'] }}%;"
                                         aria-valuenow="{{ $p['percent'] }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- ===================== --}}
            {{-- KOMENTARZE (Challenge) --}}
            {{-- ===================== --}}
            <div class="card shadow-lg border-0 mt-4"
                 style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                <div class="card-body text-white">

                    <h5 class="mb-3">
                        Komentarze ({{ $comments->count() }})
                    </h5>

                    {{-- LISTA --}}
                    @if($comments->isEmpty())
                        <p class="text-white-50 small mb-0">
                            Brak komentarzy. Bądź pierwszy 🙂
                        </p>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($comments as $c)
                                <div class="p-3 rounded" style="background:rgba(2,6,23,0.6);">
                                    <div class="d-flex justify-content-between align-items-start gap-2">

                                        <div>
                                            <div class="fw-semibold small">
                                                {{ $c->user_name ?? 'Użytkownik' }}
                                                <span class="text-white-50 fw-normal ms-2">
                                                    {{ \Illuminate\Support\Carbon::parse($c->created_at)->format('d.m.Y H:i') }}
                                                </span>
                                            </div>

                                            <div class="mt-2">
                                                {{ $c->content }}
                                            </div>
                                        </div>

                                        {{-- USUŃ (autor komentarza lub organizator wyzwania) --}}
                                        @if(auth()->check() && (
                                            auth()->id() === (int)$c->user_id ||
                                            auth()->id() === (int)$challenge->created_by
                                        ))
                                            <form method="POST"
                                                  action="{{ route('challengeComments.destroy', $c->id) }}"
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
                        @if(($myParticipantStatus ?? null) === 'active')
                            <form method="POST" action="{{ route('challenges.comments.store', $challenge->id) }}">
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
                            <div class="alert alert-secondary mb-0">
                                Nie możesz dodać komentarza, bo Twoje uczestnictwo w tym wyzwaniu jest zakończone.
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>
