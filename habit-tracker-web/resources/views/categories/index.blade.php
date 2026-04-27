<x-app-layout>

    <div class="container py-5 text-white">

        {{-- Nagłówek + przycisk dodawania --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1">Kategorie celów</h1>
                <p class="opacity-75 mb-0">
                    Zarządzaj kategoriami swoich celów. Globalne są dostępne dla wszystkich,
                    a Twoje własne widzisz tylko Ty.
                </p>
            </div>

            <a href="{{ route('categories.create') }}"
               class="btn text-white"
               style="background: linear-gradient(90deg,#7c3aed,#3b82f6); border-radius: 999px; padding-inline: 20px;">
                <i class="bi bi-plus-circle me-1"></i> Dodaj kategorię
            </a>
        </div>

        {{-- Komunikat sukcesu --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Karta z listą kategorii --}}
        <div class="card shadow-lg border-0"
             style="background: rgba(15,23,42,0.9); backdrop-filter: blur(12px);">
            <div class="card-body">

                @php
                    $userId = Auth::id();
                @endphp

                @if ($categories->isEmpty())
                    <p class="text-white-50 mb-0">
                        Nie masz jeszcze żadnych kategorii. Używane będą tylko kategorie globalne.
                        Możesz dodać własną kategorię przyciskiem powyżej.
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-borderless align-middle mb-0">
                            <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 30%;">Nazwa</th>
                                <th style="width: 20%;">Kolor</th>
                                <th style="width: 20%;">Typ</th>
                                <th class="text-end" style="width: 25%;">Akcje</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    {{-- ID / lp. --}}
                                    <td class="text-white-50">
                                        {{ $loop->iteration }}
                                    </td>

                                    {{-- Nazwa --}}
                                    <td>
                                        {{ $category->name }}
                                    </td>

                                    {{-- Kolor (badge) --}}
                                    {{-- Kolor (badge z nazwą kategorii) --}}
<td>
    <span class="badge rounded-pill"
          style="
              background-color: {{ $category->color }};
              color: #0f172a;
              padding-inline: 14px;
              font-weight: 600;
          ">
        {{ $category->name }}
    </span>
</td>


                                    {{-- Typ kategorii --}}
                                    <td>
                                        @if (is_null($category->user_id))
                                            <span class="badge bg-secondary rounded-pill">
                                                Globalna
                                            </span>
                                        @elseif ($category->user_id === $userId)
                                            <span class="badge bg-info rounded-pill">
                                                Moja
                                            </span>
                                        @else
                                            {{-- teoretycznie nie powinniśmy tu trafić, ale na wszelki --}}
                                            <span class="badge bg-info rounded-pill">
                                                Innego użytkownika
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Akcje (edycja/usuwanie tylko dla swoich) --}}
                                    <td class="text-end">

                                        @if ($category->user_id === $userId)
                                            <a href="{{ route('categories.edit', $category->id) }}"
                                               class="btn btn-sm btn-outline-light me-2">
                                                <i class="bi bi-pencil-square"></i> Edytuj
                                            </a>

                                            <form action="{{ route('categories.delete', $category->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Na pewno usunąć tę kategorię?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i> Usuń
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-white-50 small">
                                                (tylko do odczytu)
                                            </span>
                                        @endif

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

</x-app-layout>
