<x-app-layout>
    <div class="container py-4 text-white">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h1 class="fw-bold mb-1">Moje wspólne cele</h1>
                <div class="text-white-50 small">Lista challenge, w których uczestniczysz.</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('goals.create') }}" class="btn btn-success btn-sm">
                    + Nowy wspólny cel
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                    Dashboard
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($challenges->isEmpty())
            <div class="alert alert-info">
                Nie uczestniczysz jeszcze w żadnym wspólnym celu.
            </div>
        @else
            <div class="card border-0 shadow" style="background: rgba(15,23,42,0.85);">
                <div class="card-body">
                    @foreach($challenges as $c)
                        <div class="p-3 rounded mb-3" style="background: rgba(255,255,255,0.06);">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $c->title }}</div>
                                    <div class="text-white-50 small">
                                        {{ \Illuminate\Support\Carbon::parse($c->start_date)->toDateString() }}
                                        →
                                        {{ \Illuminate\Support\Carbon::parse($c->end_date)->toDateString() }}
                                        @if(!empty($c->owner_name))
                                            • Owner: {{ $c->owner_name }}
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ route('challenges.show', $c->id) }}"
                                   class="btn btn-sm btn-outline-light">
                                    Szczegóły
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
