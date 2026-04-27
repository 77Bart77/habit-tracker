<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Habit Tracker 2.0</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <!-- Scripts / CSS (Tailwind/Bootstrap – jak masz ustawione w app.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-vh-100 d-flex flex-column" 
         >

        {{-- NAVBAR --}}
       <nav class="navbar navbar-expand-md navbar-dark px-3 sticky-top"
     style="
        background: rgba(0,0,0,0.35);
        backdrop-filter: blur(16px) saturate(140%);
        -webkit-backdrop-filter: blur(16px) saturate(140%);
        border-bottom: 1px solid rgba(255,255,255,0.15);
        padding-top: 4px;
        padding-bottom: 4px;
     ">
    <div class="container-fluid">

        {{-- LOGO + NAZWA --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}"
           style="gap: 12px;">
            <img src="{{ asset('images/logo2.png') }}"
                 style="
                    height: 82px;
                    width: auto;
                    display: block;
                 ">
            <span class="fw-bold"
                  style="font-size: 1.45rem; letter-spacing: 0.06em;">
                Habit Tracker 2.0
            </span>
        </a>

        {{-- HAMBURGER --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENU --}}
        <div class="collapse navbar-collapse" id="mainNavbar">

            {{-- LEWA STRONA --}}
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('goals.index') }}">
                        <i class="bi bi-check2-square"></i> Moje cele
                    </a>
                </li>

                


                <li class="nav-item">
    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('categories.index') }}">
        <i class="bi bi-tags"></i> Kategorie
    </a>
</li>


                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('goals.public') }}">
                        <i class="bi bi-globe2"></i> Publiczne cele
                    </a>
                </li>

                <li class="nav-item">
    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('friends.index') }}">
        <i class="bi bi-people"></i> Znajomi
    </a>
</li>

@if(auth()->check() && auth()->user()->hasRole('admin'))
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-shield-lock"></i> Admin
        </a>
    </li>
@endif


            </ul>

            {{-- PRAWA STRONA --}}
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person-circle"></i> Profil
                    </a>
                </li>

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="ms-md-2">
                        @csrf
                        <button type="submit"
                                class="btn btn-sm text-white d-flex align-items-center gap-2"
                                style="
                                    background: linear-gradient(90deg,#7c3aed,#3b82f6);
                                    border:none;
                                    border-radius:8px;
                                    padding:6px 16px;
                                ">
                            <i class="bi bi-box-arrow-right"></i> Wyloguj
                        </button>
                    </form>
                </li>
            </ul>

        </div>
    </div>
</nav>


        {{-- GŁÓWNA TREŚĆ STRONY --}}
        <main class="flex-grow-1 py-4">
            <div class="container">
                {{ $slot }}
            </div>
        </main>

        {{-- STOPKA (taka jak na welcome) --}}
        <footer class="footer text-center text-white-50 py-3 mt-auto">
            Habit Tracker 2.0 © {{ date('Y') }} · Wszelkie prawa zastrzeżone
        </footer>
    </div>
</body>
</html>
