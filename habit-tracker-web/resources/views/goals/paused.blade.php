<x-app-layout>

    <div class="d-flex justify-content-between align-items-center mb-4 text-white">
        <div>
            <h1 class="fw-bold mb-1">Wstrzymane cele</h1>
            <p class="opacity-75 mb-0">
                Tutaj znajdziesz cele, które zostały wstrzymane. 
                Możesz je edytować, wznowić z nowymi datami albo trwale usunąć.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('goals.index') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Powrót do celów
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($goals->isEmpty())
        <div class="card shadow-lg border-0"
             style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
            <div class="card-body text-center text-white-50">
                Nie masz żadnych wstrzymanych celów.
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($goals as $goal)
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0"
                         style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                        <div class="card-body text-white d-flex flex-column">

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

                                <span class="badge bg-secondary">
                                    Wstrzymany
                                </span>
                            </div>

                            {{-- Opis --}}
                            <p class="text-white-50 small mb-2">
                                {{ $goal->description ?: 'Brak opisu.' }}
                            </p>

                            {{-- Daty --}}
                            <p class="text-white-50 small mb-3">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $goal->start_date?->format('d.m.Y') }}
                                –
                                {{ $goal->end_date?->format('d.m.Y') }}
                            </p>

                            <div class="mt-auto d-flex flex-wrap gap-2">

                                {{-- Podgląd --}}
                                <a href="{{ route('goals.show', $goal->id) }}"
                                   class="btn btn-sm btn-outline-light">
                                    <i class="bi bi-eye me-1"></i> Szczegóły
                                </a>

                                {{-- Edytuj i wznów (przejście do edit) --}}
                                <a href="{{ route('goals.edit', $goal->id) }}"
                                   class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-play-circle me-1"></i> Edytuj i wznów
                                </a>

                                {{-- Usuń cel (twardo) --}}
                                <form method="POST"
                                      action="{{ route('goals.destroy', $goal->id) }}"
                                      onsubmit="return confirm('Na pewno trwale usunąć ten cel? Tej operacji nie można cofnąć.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i> Usuń
                                    </button>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-app-layout>
