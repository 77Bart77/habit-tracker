<x-app-layout>

    <div class="row justify-content-center">
        <div class="col-lg-10 text-white">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold mb-1">{{ $goal->title }}</h1>

                    @if ($goal->category)
                        <span class="badge me-2"
                            style="background:{{ $goal->category->color ?? '#64748b' }};border-radius:999px;">
                            {{ $goal->category->name }}
                        </span>
                    @endif

                    <span class="badge bg-{{ $statusColor }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="ms-3 d-none d-md-block">
                    <img src="{{ asset('images/robot.png') }}" alt="Przewodnik Habit Tracker"
                        style="height:70px;width:auto;">
                </div>
            </div>

            {{-- INFO CARD --}}
            <div class="card shadow-lg border-0 mb-4" style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                <div class="card-body">

                    {{-- Opis --}}
                    @if ($goal->description)
                        <p class="mb-3 text-white">{{ $goal->description }}</p>
                    @else
                        <p class="mb-3 text-white fst-italic" style="opacity:.75;">Brak opisu.</p>
                    @endif

                    {{-- Daty --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="small text-white" style="opacity:.75;">Data rozpoczęcia</div>
                            <div class="text-white">{{ $goal->start_date?->format('d.m.Y') }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="small text-white" style="opacity:.75;">Data zakończenia</div>
                            <div class="text-white">{{ $goal->end_date?->format('d.m.Y') }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="small text-white" style="opacity:.75;">Intensywność</div>
                            <div class="text-white">{{ $intervalLabel }}</div>
                        </div>
                    </div>

                    {{-- Postęp --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-white mb-1" style="opacity:.75;">
                            <span>Postęp celu</span>
                            <span>{{ $percent }}%</span>
                        </div>

                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    {{-- Przyciski --}}
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('goals.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Powrót do listy
                        </a>

                        @if ($goal->status !== \App\Models\Goal::STATUS_FINISHED)
                            <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-pencil-square me-1"></i> Edytuj
                            </a>
                        @endif

                        {{-- Pauza / Wznowienie --}}
                        @if ($goal->status === \App\Models\Goal::STATUS_ACTIVE)
                            <form method="POST" action="{{ route('goals.delete', $goal->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pause-circle me-1"></i> Wstrzymaj
                                </button>
                            </form>
                        @elseif($goal->status === \App\Models\Goal::STATUS_PAUSED)
                            <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-play-circle me-1"></i> Edytuj i wznów
                            </a>
                        @endif

                        {{-- Usuń cel --}}
                        <form method="POST" action="{{ route('goals.destroy', $goal->id) }}"
                            onsubmit="return confirm('Na pewno trwale usunąć ten cel? Tej operacji nie można cofnąć.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash me-1"></i> Usuń cel
                            </button>
                        </form>
                    </div>

                    {{-- PRO --}}
                    <div class="mt-3">
                        @if ($canShowProSection)

                            @if (!$proReq)
                                <form method="POST" action="{{ route('proRequests.store', $goal->id) }}">
                                    @csrf
                                    <button class="btn btn-outline-primary btn-sm">
                                        ⭐ Zgłoś do PRO (weryfikacja)
                                    </button>
                                </form>
                            @else
                                <div class="d-flex flex-column gap-2">
                                    <span class="badge bg-{{ $proBadge }}">
                                        PRO: {{ strtoupper($proReq->status) }}
                                    </span>

                                    @if (!empty($proReq->admin_note) && in_array($proReq->status, ['approved', 'rejected']))
                                        <div class="small text-white" style="opacity:.9;">
                                            <span class="text-white" style="opacity:.75;">Notatka admina:</span>
                                            {{ $proReq->admin_note }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                        @endif
                    </div>

                </div>
            </div>

            {{-- SEKCJA WYKONANO DZIŚ --}}
            @if ($goal->status === \App\Models\Goal::STATUS_ACTIVE)
                <div class="card shadow-lg border-0 mb-4"
                    style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                    <div class="card-body">
                        <h5 class="text-white mb-2">Dzisiejszy postęp</h5>

                        @if ($todayDay && !$todayDone)
                            <p class="text-white small mb-3" style="opacity:.9;">
                                Odhacz ten cel jako wykonany dzisiaj 💪
                            </p>

                            <form method="POST" action="{{ route('goals.markCompletedToday', $goal->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" style="border-radius:999px;">
                                    <i class="bi bi-check2-circle me-1"></i> Odhacz jako wykonany
                                </button>
                            </form>
                        @elseif($todayDone)
                            <div class="alert alert-success mb-0">
                                Dzisiejszy dzień jest już oznaczony jako <strong>wykonany</strong> ✅
                            </div>
                        @else
                            <div class="alert alert-secondary mb-0">
                                Dziś nie jest dniem realizacji tego celu.
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- OŚ CZASU --}}
            <div class="card shadow-lg border-0" style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                <div class="card-body">
                    <h5 class="mb-3 text-white">Oś czasu realizacji</h5>

                    @foreach ($timelineDays as $d)
                        <div
                            class="d-flex justify-content-between align-items-start py-2 border-bottom border-secondary">
                            <div>

                                <div class="fw-semibold text-white">
                                    {{ $d->formatted }}
                                </div>

                                @if ($d->note)
                                    <div class="mt-1 small text-white" style="opacity:.95;">
                                        <strong>Notatka:</strong> {{ $d->note }}
                                    </div>
                                @endif

                                
                                {{-- ZAŁĄCZNIKI --}}
                                @php($atts = $d->attachmentsVm ?? [])
                                @if (count($atts) > 0)
                                    <div class="mt-2">
                                        <div class="small text-white mb-1" style="opacity:.85;">
                                            Załączniki ({{ count($atts) }})
                                        </div>

                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($atts as $att)
                                                @if (($att['kind'] ?? null) === 'image')
                                                    <a href="{{ $att['url'] }}" target="_blank" class="d-block"
                                                        style="text-decoration:none;">
                                                        <img src="{{ $att['url'] }}"
                                                            alt="{{ $att['name'] ?? 'plik' }}"
                                                            style="width:120px;height:120px;object-fit:cover;border-radius:12px;border:1px solid rgba(148,163,184,0.25);">
                                                    </a>
                                                @elseif (($att['kind'] ?? null) === 'video')
                                                    <div style="width:220px;max-width:100%;">
                                                        <video controls
                                                            style="width:220px;max-width:100%;border-radius:12px;border:1px solid rgba(148,163,184,0.25);">
                                                            <source src="{{ $att['url'] }}"
                                                                type="{{ $att['mime'] ?? 'video/mp4' }}">
                                                        </video>
                                                        <div class="small text-white mt-1 text-truncate"
                                                            style="max-width:220px;opacity:.85;">
                                                            {{ $att['name'] ?? 'wideo' }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <a href="{{ $att['url'] }}" target="_blank"
                                                        class="btn btn-sm btn-outline-light">
                                                        Pobierz: {{ $att['name'] ?? 'plik' }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                {{-- PRZYCISKI --}}
                                <div class="mt-2 d-flex gap-2 flex-wrap">
                                    <a href="{{ route('goals.editNote', ['goal' => $goal->id, 'day' => $d->id]) }}"
                                        class="btn btn-sm btn-outline-light">
                                        @if ($d->note)
                                            Edytuj notatkę / dowód
                                        @else
                                            Dodaj notatkę / dowód
                                        @endif
                                    </a>

                                    @if ($d->note)
                                        <form method="POST"
                                            action="{{ route('goals.deleteNote', ['goal' => $goal->id, 'day' => $d->id]) }}"
                                            onsubmit="return confirm('Na pewno usunąć tę notatkę?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Usuń notatkę
                                            </button>
                                        </form>
                                    @endif
                                </div>

                            </div>

                            <span class="badge bg-{{ $d->badge }}">
                                {{ $d->label }}
                            </span>
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- KOMENTARZE --}}
            <div class="card shadow-lg border-0 mt-4"
                style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                <div class="card-body text-white">

                    <h5 class="mb-3">
                        Komentarze ({{ ($goal->comments ?? collect())->count() }})
                    </h5>

                    @if (($goal->comments ?? collect())->isEmpty())
                        <p class="small mb-0 text-white" style="opacity:.75;">
                            Brak komentarzy.
                        </p>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach ($goal->comments->sortByDesc('created_at') as $comment)
                                <div class="p-3 rounded" style="background:rgba(2,6,23,0.6);">

                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <div class="fw-semibold small text-white">
                                                {{ $comment->user->name ?? 'Użytkownik' }}
                                                <span class="fw-normal ms-2 text-white" style="opacity:.75;">
                                                    {{ $comment->created_at?->format('d.m.Y H:i') }}
                                                </span>
                                            </div>

                                            <div class="mt-2 text-white" style="opacity:.95;">
                                                {{ $comment->content }}
                                            </div>
                                        </div>

                                        @if (auth()->check() && (auth()->id() === $comment->user_id || auth()->id() === $goal->user_id))
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
                        @if (auth()->check())
                            <form method="POST" action="{{ route('goals.comments.store', $goal->id) }}">
                                @csrf

                                <textarea name="content" class="form-control mb-2" rows="3" maxlength="1000"
                                    placeholder="Napisz komentarz..."
                                    style="background:rgba(15,23,42,0.7);color:#fff;border-color:rgba(148,163,184,0.25);"></textarea>

                                <button class="btn btn-primary btn-sm">
                                    Dodaj komentarz
                                </button>
                            </form>
                        @else
                            <p class="small mb-0 text-white" style="opacity:.75;">
                                Zaloguj się, aby dodać komentarz.
                            </p>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>
