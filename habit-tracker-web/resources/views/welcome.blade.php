<!doctype html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <title>Habit Tracker 2.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/js/app.js'])
</head>

<body>

    <div class="container-fluid vh-100 d-flex align-items-center">
        <div class="row w-100">


            <div class="col-md-6 d-flex flex-column justify-content-center text-white ps-md-5 mb-5 mb-md-0">


                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="logo-hover"
                        style="width:300px; max-width:90%;">
                </div>

                <h1 class="fw-bold mb-3 text-center">Witaj w Habit Tracker 2.0</h1>

                <p class="lead opacity-75 text-center">
                    Aplikacja, która pomoże Ci codziennie rozwijać dobre nawyki, śledzić postępy
                    i budować lepszą wersję siebie – krok po kroku.
                </p>

            </div>



            <div class="col-md-4 offset-md-1 d-flex align-items-center">
                <div class="card shadow-lg p-4 w-100"
                    style="border-radius: 16px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">

                    <h3 class="text-white mb-4 text-center fw-semibold">Zaloguj się</h3>
                    @if (session('error'))
                        <div class="alert alert-danger text-center mb-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label text-white">E-mail</label>
                            <input type="email" name="email"
                                class="form-control bg-white text-dark border-secondary" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Hasło</label>
                            <input type="password" name="password"
                                class="form-control bg-white text-dark border-secondary" required>
                        </div>

                        <button class="btn w-100 mt-2 border-0 text-white"
                            style="background: linear-gradient(90deg, #7c3aed, #3b82f6);">
                            Zaloguj się
                        </button>


                    </form>


                    @if (Route::has('register'))
                        <p class="text-center text-white mt-3 mb-0">
                            Nie masz konta?
                            <a href="{{ route('register') }}" class="text-white text-decoration-underline">Zarejestruj
                                się</a>
                        </p>
                    @endif

                </div>
            </div>

        </div>
    </div>

    <footer class="footer text-center text-white-50 py-3">
        Habit Tracker 2.0 © {{ date('Y') }} · Wszelkie prawa zastrzeżone
    </footer>


</body>

</html>
