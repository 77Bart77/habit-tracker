<x-app-layout>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5 text-white">

            {{-- Nagłówek --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="fw-bold mb-1">Edytuj kategorię</h1>
                    <p class="opacity-75 mb-0">
                        Zmień nazwę lub kolor tej kategorii.
                    </p>
                </div>

                <div class="ms-3 d-none d-md-block">
                    <img src="{{ asset('images/robot.png') }}"
                         alt="Przewodnik Habit Tracker"
                         class="guide-robot"
                         style="height: 70px; width: auto;">
                </div>
            </div>

            {{-- Błędy --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Wystąpiły błędy w formularzu:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formularz --}}
            <div class="card shadow-lg border-0"
                 style="background: rgba(15,23,42,0.9); backdrop-filter: blur(12px);">
                <div class="card-body">

                    <form method="POST" action="{{ route('goals.update', $goal->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Jeśli cel jest wstrzymany, to po zapisaniu ustawiamy go na ACTIVE --}}
    @if($goal->status === \App\Models\Goal::STATUS_PAUSED)
        <input type="hidden" name="status" value="{{ \App\Models\Goal::STATUS_ACTIVE }}">
    @endif

                        {{-- NAZWA --}}
                        <div class="mb-3">
                            <label for="name" class="form-label text-white">Nazwa kategorii</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control bg-white text-dark border-secondary"
                                   value="{{ old('name', $category->name) }}"
                                   maxlength="30"
                                   required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- WYBÓR KOLORU --}}
                        <div class="mb-3">
                            <label for="color" class="form-label text-white">Kolor znacznika</label>
                            <input type="color"
                                   name="color"
                                   id="color"
                                   value="{{ old('color', $category->color) }}"
                                   class="form-control form-control-color"
                                   style="width: 100px; height: 48px; padding: 4px;">
                            @error('color')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                            <div class="form-text text-white-50">
                                Ten kolor będzie wyświetlany jako odznaka kategorii.
                            </div>
                        </div>

                        {{-- PRZYCISKI --}}
                        <div class="d-flex justify-content-between mt-4">

                            <a href="{{ route('categories.index') }}"
                               class="btn btn-outline-light">
                                Anuluj
                            </a>

                            <button type="submit"
                                    class="btn text-white"
                                    style="
                                        background: linear-gradient(90deg,#7c3aed,#3b82f6);
                                        border:none;
                                        border-radius: 999px;
                                        padding-inline: 24px;
                                    ">
                                Zapisz zmiany
                            </button>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
