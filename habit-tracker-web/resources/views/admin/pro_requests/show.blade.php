<x-app-layout>
    @php
        /** @var \App\Models\ProRequest $proRequest */
        $req  = $proRequest;
        $goal = $req->goal;

        $status = $req->status ?? 'pending';

        $badge = match($status) {
            \App\Models\ProRequest::STATUS_APPROVED => 'success',
            \App\Models\ProRequest::STATUS_REJECTED => 'danger',
            default => 'warning',
        };

        $percent = (int)($goal->progress_percent ?? 0);

        $isVideo = fn (?string $mime) => $mime && str_starts_with($mime, 'video/');
        $isImage = fn (?string $mime) => $mime && str_starts_with($mime, 'image/');
    @endphp

    <div class="container py-4 text-white">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1 text-white">Weryfikacja PRO</h1>
                <div class="text-white" style="opacity:.8;">
                    Zgłoszenie #{{ $req->id }}
                    <span class="badge bg-{{ $badge }} ms-2">
                        {{ strtoupper($status) }}
                    </span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.pro_requests.index') }}"
                   class="btn btn-outline-light btn-sm">
                    ← Wróć
                </a>

                @if($goal)
                    <a href="{{ route('goals.show', $goal->id) }}"
                       class="btn btn-outline-light btn-sm">
                        Otwórz cel
                    </a>
                @endif
            </div>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="alert alert-success text-white">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger text-white">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">

            {{-- LEWA STRONA --}}
            <div class="col-lg-5">

                {{-- USER INFO --}}
                <div class="card border-0 shadow-lg mb-4"
                     style="background:rgba(15,23,42,0.95);">
                    <div class="card-body text-white">

                        <h5 class="fw-bold mb-3 text-white">Zgłaszający</h5>

                        <div>Email: <strong>{{ $req->user->email ?? '-' }}</strong></div>

                        @if(!empty($req->user?->name))
                            <div class="mt-1">Nazwa: <strong>{{ $req->user->name }}</strong></div>
                        @endif

                        <hr class="border-secondary">

                        <div>Zgłoszono:
                            <strong>
                                {{ $req->requested_at?->format('d.m.Y H:i') ?? '-' }}
                            </strong>
                        </div>

                        <div class="mt-1">Rozpatrzono:
                            <strong>
                                {{ $req->reviewed_at?->format('d.m.Y H:i') ?? '-' }}
                            </strong>
                        </div>

                        @if(!empty($req->admin_note))
                            <div class="mt-3">
                                <strong>Notatka admina:</strong>
                                <div class="mt-1">{{ $req->admin_note }}</div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- GOAL SUMMARY --}}
                <div class="card border-0 shadow-lg"
                     style="background:rgba(15,23,42,0.95);">
                    <div class="card-body text-white">

                        <h5 class="fw-bold mb-3 text-white">Cel</h5>

                        <div class="fw-bold fs-5 text-white">
                            {{ $goal->title ?? '-' }}
                        </div>

                        @if(!empty($goal?->description))
                            <div class="mt-2 text-white" style="opacity:.85;">
                                {{ $goal->description }}
                            </div>
                        @endif

                        <div class="row mt-3">
                            <div class="col-6">
                                <div>Start:</div>
                                <strong>{{ $goal->start_date?->format('d.m.Y') ?? '-' }}</strong>
                            </div>

                            <div class="col-6">
                                <div>Koniec:</div>
                                <strong>{{ $goal->end_date?->format('d.m.Y') ?? '-' }}</strong>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div>Postęp: <strong>{{ $percent }}%</strong></div>
                            <div class="progress mt-1" style="height:6px;">
                                <div class="progress-bar bg-success"
                                     style="width: {{ $percent }}%">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- PRAWA STRONA --}}
            <div class="col-lg-7">

                {{-- DECYZJA --}}
                <div class="card border-0 shadow-lg mb-4"
                     style="background:rgba(15,23,42,0.95);">
                    <div class="card-body text-white">

                        <h5 class="fw-bold mb-3 text-white">Decyzja</h5>

                        @if($status === \App\Models\ProRequest::STATUS_PENDING)

                            <form method="POST"
                                  action="{{ route('admin.pro_requests.approve', $req) }}"
                                  class="d-flex gap-2 mb-2">
                                @csrf
                                @method('PATCH')

                                <input name="admin_note"
                                       class="form-control form-control-sm"
                                       placeholder="Notatka (opcjonalnie)"
                                       style="background:#111;color:#fff;border:1px solid #444;">

                                <button class="btn btn-success btn-sm">
                                    Approve
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('admin.pro_requests.reject', $req) }}"
                                  class="d-flex gap-2">
                                @csrf
                                @method('PATCH')

                                <input name="admin_note"
                                       class="form-control form-control-sm"
                                       placeholder="Powód odrzucenia"
                                       style="background:#111;color:#fff;border:1px solid #444;">

                                <button class="btn btn-outline-danger btn-sm">
                                    Reject
                                </button>
                            </form>

                        @else
                            <div class="alert alert-secondary text-white mb-0">
                                Zgłoszenie zostało już rozpatrzone.
                            </div>
                        @endif

                    </div>
                </div>

                {{-- OŚ CZASU --}}
                <div class="card border-0 shadow-lg"
                     style="background:rgba(15,23,42,0.95);">
                    <div class="card-body text-white">

                        <h5 class="fw-bold mb-3 text-white">
                            Dowody / Oś czasu
                        </h5>

                        @php
                            $days = ($goal->days ?? collect())->sortBy('date');
                        @endphp

                        @if($days->isEmpty())
                            <div class="alert alert-secondary text-white mb-0">
                                Brak dni do wyświetlenia.
                            </div>
                        @else
                            @foreach($days as $day)
                                <div class="mb-3 p-3 rounded"
                                     style="background:rgba(2,6,23,0.6);border:1px solid #333;">

                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>{{ $day->date?->format('d.m.Y') ?? '-' }}</strong>

                                        <span class="badge bg-secondary">
                                            {{ strtoupper($day->status ?? '') }}
                                        </span>
                                    </div>

                                    @if(!empty($day->note))
                                        <div class="mb-2">
                                            <strong>Notatka:</strong>
                                            {{ $day->note }}
                                        </div>
                                    @endif

                                    @if(!empty($day->attachments) && $day->attachments->count())
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($day->attachments as $att)
                                                @php
                                                    $url = asset('storage/'.$att->file_path);
                                                @endphp

                                                @if($isImage($att->mime_type))
                                                    <img src="{{ $url }}"
                                                         style="width:100px;height:100px;object-fit:cover;border-radius:8px;">
                                                @else
                                                    <a href="{{ $url }}"
                                                       target="_blank"
                                                       class="btn btn-outline-light btn-sm">
                                                        Pobierz
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>