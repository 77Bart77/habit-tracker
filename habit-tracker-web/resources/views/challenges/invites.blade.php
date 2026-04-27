<x-app-layout>
    <div class="container py-4 text-white">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="fw-bold mb-0">Zaproszenia do wspólnych celów</h1>
            <a href="{{ route('goals.index') }}" class="btn btn-outline-light btn-sm">
                Wróć do celów
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(isset($invites) && $invites->count() > 0)
            <div class="card border-0 shadow" style="background: rgba(15,23,42,0.9);">
                <div class="card-body">

                    @foreach($invites as $invite)
                        <div class="p-3 rounded mb-3" style="background: rgba(255,255,255,0.06);">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-bold">{{ $invite->title }}</div>
                                    <div class="text-white-50 small">
                                        Od: {{ $invite->invited_by_name }} •
                                        {{ \Illuminate\Support\Carbon::parse($invite->start_date)->toDateString() }}
                                        →
                                        {{ \Illuminate\Support\Carbon::parse($invite->end_date)->toDateString() }}
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('challenges.invites.accept', $invite->invite_id) }}">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Akceptuj</button>
                                    </form>

                                    <form method="POST" action="{{ route('challenges.invites.decline', $invite->invite_id) }}">
                                        @csrf
                                        <button class="btn btn-outline-danger btn-sm">Odrzuć</button>
                                    </form>
                                </div>
                            </div>

                            <div class="mt-2">
                                <a class="link-light small"
                                   href="{{ route('challenges.show', $invite->challenge_id) }}">
                                    Zobacz szczegóły
                                </a>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @else
            <div class="alert alert-info">
                Nie masz aktualnie żadnych zaproszeń.
            </div>
        @endif

    </div>
</x-app-layout>
