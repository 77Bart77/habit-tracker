<x-app-layout>

    @php
        $canComment = auth()->check() && (auth()->id() === $userProfile->id || $areFriends);
    @endphp

    <div class="row g-4">

        {{-- ===================== --}}
        {{-- NAGŁÓWEK PROFILU --}}
        {{-- ===================== --}}
        <div class="col-12">
            <div class="card shadow-sm border-0"
                 style="background: rgba(15,23,42,0.85); backdrop-filter: blur(10px);">
                <div class="card-body text-white d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div>
                        <h1 class="h4 fw-bold mb-1">
                            {{ $userProfile->name ?? 'Użytkownik' }}
                        </h1>
                        <div class="text-white-50 small">
                            ID: {{ $userProfile->id }}
                        </div>
                    </div>

                    <div class="text-white-50 small">
    ID: {{ $userProfile->id }}
</div>

{{-- PUNKTY + LEVEL --}}
<div class="mt-2 d-flex flex-wrap gap-2">
    <span class="badge bg-warning text-dark">
        ⭐ Punkty: {{ $totalPoints ?? 0 }}
    </span>

    <span class="badge bg-primary">
        🏅 Level: {{ $level ?? 1 }}
    </span>
</div>


                    {{-- PRZYCISKI ZNAJOMOŚCI --}}
                    <div>
                        @if(auth()->id() === $userProfile->id)
                            <span class="badge bg-info">To Twój profil</span>
                        @else

                            @if($areFriends)
                                <span class="badge bg-success me-2">Znajomy</span>

                                <form method="POST"
                                      action="{{ route('friends.remove', $userProfile->id) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        Usuń ze znajomych
                                    </button>
                                </form>

                            @elseif($hasPending)
                                <span class="badge bg-secondary">Zaproszenie w toku</span>

                            @else
                                <form method="POST"
                                      action="{{ route('friends.request.send', $userProfile->id) }}"
                                      class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-primary" type="submit">
                                        Dodaj do znajomych
                                    </button>
                                </form>
                            @endif

                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- PUBLICZNE CELE --}}
        {{-- ===================== --}}
        <div class="col-12">
            <div class="card shadow-sm border-0"
                 style="background: rgba(15,23,42,0.85); backdrop-filter: blur(10px);">
                <div class="card-body text-white">

                    <h5 class="card-title mb-3">Publiczne cele (ostatnie 10)</h5>

                    @if($publicGoals->isEmpty())
                        <p class="text-white-50 mb-0">
                            Ten użytkownik nie ma publicznych celów.
                        </p>
                    @else

                        @foreach($publicGoals as $goal)
                            @php
                                // Bezpiecznie, jeśli relacja comments jest dociągnięta
                                $commentsCount = $goal->comments?->count() ?? 0;
                                $collapseId = 'commentsGoal'.$goal->id;
                            @endphp

                            <div class="mb-3 pb-3 border-bottom border-secondary">

                                <div class="d-flex justify-content-between align-items-center gap-3">

                                    {{-- LEWA STRONA --}}
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $goal->title }}
                                        </div>

                                        @if($goal->category)
                                            <span class="badge"
                                                  style="background: {{ $goal->category->color ?? '#64748b' }};
                                                         border-radius: 999px;">
                                                {{ $goal->category->name }}
                                            </span>
                                        @endif

                                        <div class="small text-white-50 mt-1">
                                            Komentarze: {{ $commentsCount }}
                                        </div>
                                    </div>

                                    {{-- PRAWA STRONA --}}
                                    <div class="d-flex gap-2 align-items-center flex-shrink-0">

                                        {{-- LIKE --}}
                                        <x-goal-like-button
                                            :goal="$goal"
                                            :liked-goal-ids="($likedGoalIds ?? [])"
                                        />

                                        {{-- SZCZEGÓŁY --}}
                                        <a href="{{ route('goals.public.show', $goal->id) }}"
                                           class="btn btn-sm btn-outline-light">
                                            Zobacz cel
                                        </a>

                                        {{-- TOGGLE KOMENTARZY --}}
                                        <button class="btn btn-sm btn-outline-info"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="false"
                                                aria-controls="{{ $collapseId }}">
                                            Komentarze
                                        </button>

                                    </div>

                                </div>

                                {{-- ===================== --}}
                                {{-- KOMENTARZE (collapse) --}}
                                {{-- ===================== --}}
                                <div class="collapse mt-3" id="{{ $collapseId }}">
                                    <div class="card border-0"
                                         style="background: rgba(2,6,23,0.55);">
                                        <div class="card-body">

                                            {{-- LISTA KOMENTARZY --}}
                                            @if(($goal->comments?->count() ?? 0) === 0)
                                                <div class="text-white-50 small">
                                                    Brak komentarzy. Bądź pierwszy 🙂
                                                </div>
                                            @else
                                                <div class="d-flex flex-column gap-3">
                                                    @foreach($goal->comments->sortByDesc('created_at') as $comment)
                                                        <div class="p-3 rounded"
                                                             style="background: rgba(15,23,42,0.75);">
                                                            <div class="d-flex justify-content-between align-items-start gap-2">
                                                                <div>
                                                                    <div class="fw-semibold small">
                                                                        {{ $comment->user->name ?? 'Użytkownik' }}
                                                                        <span class="text-white-50 fw-normal ms-2">
                                                                            {{ optional($comment->created_at)->format('d.m.Y H:i') }}
                                                                        </span>
                                                                    </div>

                                                                    <div class="mt-2">
                                                                        {{ $comment->content }}
                                                                    </div>
                                                                </div>

                                                                {{-- usuwać może autor komentarza (i opcjonalnie właściciel profilu) --}}
                                                                @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->id() === $userProfile->id))
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

                                            {{-- DODAJ KOMENTARZ (tylko znajomi + owner) --}}
                                            <div class="mt-4">
                                                @if($canComment)
                                                    <form method="POST" action="{{ route('goals.comments.store', $goal->id) }}">
                                                        @csrf

                                                        <div class="mb-2">
                                                            <textarea name="content"
                                                                      class="form-control"
                                                                      rows="3"
                                                                      maxlength="1000"
                                                                      placeholder="Napisz komentarz..."
                                                                      style="background: rgba(15,23,42,0.7); color: #fff; border-color: rgba(148,163,184,0.25);"></textarea>
                                                        </div>

                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            Dodaj komentarz
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="text-white-50 small">
                                                        Komentarze mogą dodawać tylko znajomi (oraz właściciel profilu).
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
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
