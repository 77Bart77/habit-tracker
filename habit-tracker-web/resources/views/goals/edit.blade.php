<x-app-layout>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7 text-white">

            {{-- Nagłówek --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="fw-bold mb-1">Edytuj cel</h1>
                    <p class="opacity-75 mb-0">
                        Zaktualizuj dane celu, intensywność i ustawienia udostępniania.
                    </p>
                </div>

                <div class="ms-3 d-none d-md-block">
                    <img src="{{ asset('images/robot.png') }}"
                         alt="Przewodnik Habit Tracker"
                         class="guide-robot"
                         style="height: 80px; width: auto;">
                </div>
            </div>

            {{-- Błędy --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ups! Coś poszło nie tak.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-lg border-0"
                 style="background: rgba(15,23,42,0.9); backdrop-filter: blur(12px);">
                <div class="card-body">

                    <form method="POST"
                          action="{{ route('goals.update', $goal->id) }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- KATEGORIA --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="goal_category_id" class="form-label text-white mb-0">
                                    Kategoria celu
                                </label>

                                <a href="{{ route('categories.create') }}"
                                   class="btn btn-sm btn-outline-light d-flex align-items-center gap-1">
                                    <i class="bi bi-plus-circle"></i>
                                    <span class="d-none d-sm-inline">Dodaj kategorię</span>
                                </a>
                            </div>

                            <select name="goal_category_id" id="goal_category_id"
                                    class="form-select bg-white text-dark border-secondary" required>
                                <option value="">-- wybierz kategorię --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ (old('goal_category_id', $goal->goal_category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('goal_category_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TYTUŁ --}}
                        <div class="mb-3">
                            <label for="title" class="form-label text-white">
                                Nazwa celu
                            </label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control bg-white text-dark border-secondary"
                                   value="{{ old('title', $goal->title) }}"
                                   required
                                   maxlength="100">
                            @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- OPIS --}}
                        <div class="mb-3">
                            <label for="description" class="form-label text-white">
                                Opis (opcjonalnie)
                            </label>
                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      class="form-control bg-white text-dark border-secondary"
                                      placeholder="Opisz, na czym polega ten nawyk...">{{ old('description', $goal->description) }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ZAŁĄCZNIK (opcjonalnie kolejny) --}}
                        <div class="mb-3">
                            <label for="attachment" class="form-label text-white">
                                Dodaj kolejny załącznik (obraz lub wideo)
                            </label>
                            <input type="file"
                                   name="attachment"
                                   id="attachment"
                                   class="form-control bg-white text-dark border-secondary"
                                   accept="image/*,video/*">
                            @error('attachment')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror>
                            <div class="form-text text-white-50">
                                Jeśli wybierzesz plik, zostanie dodany jako nowy załącznik do tego celu.
                            </div>
                        </div>

                        {{-- INTENSYWNOŚĆ --}}
                        <div class="mb-3">
                            <label for="interval_days" class="form-label text-white">
                                Jak często chcesz wykonywać ten cel?
                            </label>
                            @php
                                $intervalOld = old('interval_days', $goal->interval_days ?? 1);
                            @endphp
                            <select name="interval_days" id="interval_days"
                                    class="form-select bg-white text-dark border-secondary">
                                <option value="1"  {{ $intervalOld == 1  ? 'selected' : '' }}>Codziennie</option>
                                <option value="2"  {{ $intervalOld == 2  ? 'selected' : '' }}>Co 2 dni</option>
                                <option value="3"  {{ $intervalOld == 3  ? 'selected' : '' }}>Co 3 dni</option>
                                <option value="7"  {{ $intervalOld == 7  ? 'selected' : '' }}>Raz w tygodniu</option>
                                <option value="14" {{ $intervalOld == 14 ? 'selected' : '' }}>Co 2 tygodnie</option>
                                <option value="30" {{ $intervalOld == 30 ? 'selected' : '' }}>Raz w miesiącu</option>
                            </select>
                            @error('interval_days')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DATY --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label text-white">
                                    Data rozpoczęcia
                                </label>
                                <input type="date"
                                       name="start_date"
                                       id="start_date"
                                       class="form-control bg-white text-dark border-secondary"
                                       value="{{ old('start_date', optional($goal->start_date)->format('Y-m-d')) }}">
                                @error('start_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label text-white">
                                    Data zakończenia
                                </label>
                                <input type="date"
                                       name="end_date"
                                       id="end_date"
                                       class="form-control bg-white text-dark border-secondary"
                                       value="{{ old('end_date', optional($goal->end_date)->format('Y-m-d')) }}">
                                @error('end_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- PUBLICZNY --}}
                        <div class="mb-4 form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   id="is_public" name="is_public" value="1"
                                   {{ old('is_public', $goal->is_public) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="is_public">
                                Udostępnij ten cel jako publiczny
                            </label>
                            @error('is_public')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PRZYCISKI --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('goals.index') }}" class="btn btn-outline-light">
                                Anuluj
                            </a>

                            <button type="submit"
                                    class="btn text-white"
                                    style="background: linear-gradient(90deg,#7c3aed,#3b82f6); border-radius: 999px; padding-inline: 24px;">
                                Zapisz zmiany
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>
