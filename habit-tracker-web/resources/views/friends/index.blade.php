<x-app-layout>
    <div class="row g-4">

        {{-- KOMUNIKATY --}}
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Coś poszło nie tak:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- ZAPROSZENIA DO MNIE --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0"
                 style="background: rgba(15,23,42,0.85); backdrop-filter: blur(10px);">
                <div class="card-body text-white">
                    <h5 class="card-title mb-3">Zaproszenia do Ciebie ({{ $pendingRequests->count() }})</h5>

                    @if($pendingRequests->isEmpty())
                        <p class="text-white-50 mb-0">Brak zaproszeń.</p>
                    @else
                        <div class="list-group">
                            @foreach($pendingRequests as $req)
                                <div class="list-group-item d-flex justify-content-between align-items-center"
                                     style="background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.08); color:white;">
                                    <div>
                                        <div class="fw-semibold">
                                            Od użytkownika #{{ $req->user_id }}
                                        </div>
                                        <div class="small text-white-50">
                                            ID zaproszenia: {{ $req->id }}
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        {{-- ACCEPT --}}
                                        <form method="POST" action="{{ route('friends.request.accept', $req->id) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-success" type="submit">
                                                Akceptuj
                                            </button>
                                        </form>

                                        {{-- DECLINE --}}
                                        <form method="POST" action="{{ route('friends.request.decline', $req->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                Odrzuć
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ZAPROSZENIA WYSŁANE --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0"
                 style="background: rgba(15,23,42,0.85); backdrop-filter: blur(10px);">
                <div class="card-body text-white">
                    <h5 class="card-title mb-3">Wysłane zaproszenia ({{ $sentRequests->count() }})</h5>

                    @if($sentRequests->isEmpty())
                        <p class="text-white-50 mb-0">Brak wysłanych zaproszeń.</p>
                    @else
                        <div class="list-group">
                            @foreach($sentRequests as $req)
                                <div class="list-group-item d-flex justify-content-between align-items-center"
                                     style="background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.08); color:white;">
                                    <div>
                                        <div class="fw-semibold">
                                            Do użytkownika #{{ $req->friend_id }}
                                        </div>
                                        <div class="small text-white-50">
                                            ID zaproszenia: {{ $req->id }}
                                        </div>
                                    </div>

                                    <span class="badge bg-secondary">Oczekuje</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- LISTA ZNAJOMYCH --}}
        <div class="col-12">
            <div class="card shadow-sm border-0"
                 style="background: rgba(15,23,42,0.85); backdrop-filter: blur(10px);">
                <div class="card-body text-white">
                    <h5 class="card-title mb-3">Twoi znajomi ({{ $friends->count() }})</h5>

                    @if($friends->isEmpty())
                        <p class="text-white-50 mb-0">Nie masz jeszcze znajomych.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-dark table-borderless align-middle mb-0">
                                <thead>
                                    <tr class="text-white-50">
                                        <th>Użytkownik</th>
                                        <th class="text-end">Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($friends as $friend)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">
                                                    {{ $friend->name ?? 'Użytkownik' }} (ID: {{ $friend->id }})
                                                </div>
                                                <div class="small text-white-50">
                                                    {{ $friend->email ?? '' }}
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('users.show', $friend->id) }}" class="btn btn-sm btn-outline-light">
                                                    Profil
                                                </a>

                                                <form method="POST" action="{{ route('friends.remove', $friend->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Usuń
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
