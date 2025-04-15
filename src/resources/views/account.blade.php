<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - konto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')
<div>
    <div class="container py-5">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="profile-header position-relative mb-4">
                    <!-- Usunięto przycisk edycji zdjęcia -->
                </div>
                <div class="text-center text-white">
                    <div class="position-relative d-inline-block">
                        @if(Auth::user()->photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->photo_path) }}"
                                 class="rounded-circle profile-pic" alt="Zdjęcie profilowe" width="300" height="300">
                        @else
                            <img src="{{ asset('images/includes/default_avatar.png') }}"
                                 class="rounded-circle profile-pic" alt="Zdjęcie profilowe" width="300" height="300">
                        @endif
                    </div>
                    <h3 class="mt-3 mb-1">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                    <p class="mb-3">{{ Auth::user()->age }} lat</p>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-9">
                                <div class="p-4">
                                    <div class="mb-4">
                                        <h5 class="mb-4">Informacje</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Imie</label>
                                                <p class="border rounded p-2">{{ Auth::user()->name }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nazwisko</label>
                                                <p class="border rounded p-2">{{ Auth::user()->last_name }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <p class="border rounded p-2">{{ Auth::user()->email }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Numer telefonu</label>
                                                <p class="border rounded p-2">{{ Auth::user()->phone }}</p>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">O mnie</label>
                                                <div class="border rounded p-2">{{ Auth::user()->description }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
</body>
</html>
