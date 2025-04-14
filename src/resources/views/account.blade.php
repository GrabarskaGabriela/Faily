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
                    <div class="position-absolute top-0 end-0 p-3">
                        <a href="{{ route('profile.edit-photo') }}" class="btn btn-dark">
                            <i class="fas fa-edit me-2"></i>Edytuj zdjęcie
                        </a>
                    </div>
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
                                                <input type="text" class="form-control"
                                                       value="{{ Auth::user()->first_name }} ">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nazwisko</label>
                                                <input type="text" class="form-control"
                                                       value="{{ Auth::user()->last_name }} ">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control"
                                                       value="{{ Auth::user()->email }} ">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Numer telefonu</label>
                                                <input type="tel" class="form-control"
                                                       value="{{ Auth::user()->phone }} ">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">O mnie</label>
                                                <textarea class="form-control"
                                                          rows="4">{{ Auth::user()->description }} </textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6">
                                            <div class="settings-card card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-1">Weryfikacja dwuetapowa</h6>
                                                            <p class="text-muted mb-0 small">Poszerz swoją ochronę</p>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="settings-card card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-1">Powiadomienia email</h6>
                                                            <p class="text-muted mb-0 small">Dostawaj powiadomienia o
                                                                wydarzeniach w Twojej okolicy</p>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="mb-4">Ostatnia aktywność</h5>
                                        <div class="activity-item mb-3">
                                            <h6 class="mb-1">Ostatnia aktualizacja zdjęcia</h6>
                                            <p class="text-muted small mb-0">2 tygodnie temu</p>
                                        </div>
                                        <div class="activity-item mb-3">
                                            <h6 class="mb-1">Ostatnia zmiana hasła</h6>
                                            <p class="text-muted small mb-0">2 dni temu</p>
                                        </div>
                                        <div class="activity-item">
                                            <h6 class="mb-1">Ostatnio aktywny</h6>
                                            <p class="text-muted small mb-0">2 godziny temu</p>
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
