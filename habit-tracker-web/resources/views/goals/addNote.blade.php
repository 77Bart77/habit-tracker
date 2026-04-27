<x-app-layout>
@php
    $isToday = request()->routeIs('goals.noteToday'); // GET /goals/{id}/note-today
@endphp

    <div class="row justify-content-center">
        <div class="col-lg-7 text-white">

            <h1 class="fw-bold mb-3">Notatka z dzisiejszego dnia</h1>
            <p class="opacity-75 mb-3">
                Cel: <strong>{{ $goal->title }}</strong><br>
                Data: {{ \Illuminate\Support\Carbon::parse($day->date)->format('d.m.Y') }}
            </p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Ups! Coś poszło nie tak.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-lg border-0"
                 style="background:rgba(15,23,42,0.9);backdrop-filter:blur(10px);">
                <div class="card-body">

                    <form method="POST"
                    enctype="multipart/form-data"
      
                          action="{{ $isToday
            ? route('goals.saveNote', $goal->id)
            : route('goals.updateNote', [$goal->id, $day->id]) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="note" class="form-label text-white">
                                Notatka (opcjonalnie)
                            </label>
                            <textarea name="note" id="note" rows="4"
                                      class="form-control bg-white text-dark border-secondary"
                                      placeholder="Jak poszło dzisiaj?">{{ old('note', $day->note) }}</textarea>
                        </div>
                        <div class="mb-3">
    <label for="attachment" class="form-label text-white">
        Dowód (zdjęcie lub wideo) — opcjonalnie
    </label>

    <input type="file"
           name="attachment"
           id="attachment"
           class="form-control bg-white text-dark border-secondary"
           accept="image/jpeg,image/png,image/webp,video/mp4,video/quicktime">

    <div class="text-white-50 small mt-1">
        Max 20MB. (jpg/png/webp/mp4/mov)
    </div>
</div>


                        <div class="d-flex justify-content-between">
                            <a href="{{ route('goals.index') }}" class="btn btn-outline-light">
                                Powrót do listy
                            </a>

                            <button type="submit"
                                    class="btn text-white"
                                    style="background:linear-gradient(90deg,#7c3aed,#3b82f6);border-radius:999px;padding-inline:24px;">
                                Zapisz notatkę
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>

