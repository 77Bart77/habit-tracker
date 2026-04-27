<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Rejestracja — Habit Tracker 2.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/js/app.js'])
</head>
<body>

<div class="container-fluid vh-100 d-flex align-items-center">
  <div class="row w-100">

    {{-- LEWA STRONA --}}
    <div class="col-md-6 d-flex flex-column justify-content-center text-white ps-md-5 mb-5 mb-md-0">

        <div class="text-center mb-4">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="logo-hover" 
                 style="width:300px; max-width:90%;">
        </div>

        <h1 class="fw-bold mb-3 text-center">Dołącz do Habit Tracker 2.0</h1>

        <p class="lead opacity-75 text-center">
            Dołącz i zacznij budować pozytywne nawyki.  
            Razem osiągniemy więcej. Zarejestruj się w kilka sekund.
        </p>

    </div>

    {{-- PRAWA STRONA --}}
    <div class="col-md-4 offset-md-1 d-flex align-items-center">
        <div class="card shadow-lg p-4 w-100" 
             style="border-radius: 16px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">

            <h3 class="text-white mb-4 text-center fw-semibold">Utwórz konto</h3>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Imię --}}
                <div class="mb-3">
                    <label class="form-label text-white">Imię</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="form-control bg-white text-dark border-secondary" required>
                    @error('name') 
                        <small class="text-danger">{{ $message }}</small> 
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label text-white">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="form-control bg-white text-dark border-secondary" required>
                    @error('email') 
                        <small class="text-danger">{{ $message }}</small> 
                    @enderror
                </div>

                {{-- Hasło --}}
                <div class="mb-3">
                    <label class="form-label text-white">Hasło</label>
                    <input type="password" name="password" 
                           class="form-control bg-white text-dark border-secondary" required>
                    @error('password') 
                        <small class="text-danger">{{ $message }}</small> 
                    @enderror
                </div>

                {{-- Potwierdzenie --}}
                <div class="mb-3">
                    <label class="form-label text-white">Powtórz hasło</label>
                    <input type="password" name="password_confirmation" 
                           class="form-control bg-white text-dark border-secondary" required>
                    @error('password_confirmation') 
                        <small class="text-danger">{{ $message }}</small> 
                    @enderror
                </div>

                {{-- Przycisk --}}
                <button class="btn w-100 mt-2 border-0 text-white"
                        style="background: linear-gradient(90deg, #7c3aed, #3b82f6);">
                    Zarejestruj się
                </button>

            </form>

            {{-- Link do logowania --}}
            <p class="text-center text-white mt-3 mb-0">
                Masz już konto?
                <a href="{{ route('welcome') }}" class="text-white text-decoration-underline">
                    Zaloguj się
                </a>
            </p>

        </div>
    </div>

  </div>
</div>

<footer class="footer text-center text-white-50 py-3">
    Habit Tracker 2.0 © {{ date('Y') }} · Wszelkie prawa zastrzeżone
</footer>

</body>
</html>
