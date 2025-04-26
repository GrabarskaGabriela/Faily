<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel użytkownika - Faily</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="row">
        @include('includes.profile_menu')

        <div class="col-md-9">
            <div class="card shadow-sm mb-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: white;">
                <div class="card-header d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%); color: white;">
                    <h4 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Podsumowanie</h4>
                    <span class="badge">Ostatnia aktualizacja: {{ now()->format('d.m.Y H:i') }}</span>
                </div>

                <div class="card-body">
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Witaj, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</h5>
                            <p class="mb-0">Jesteś zalogowany do systemu Faily</p>
                        </div>
                    </div>

                    <div class="row mb-4" >
                        <div class="col-md-4 mb-3">
                            <div class="card border-white text-white h-100" style="background: rgba(255,255,255,0.05);">
                                <div class="card-body text-center d-flex flex-column">
                                    <h5 class="card-title">
                                        <i class="fas fa-home text-white me-2"></i>Moje wydarzenia
                                    </h5>
                                    <p class="h2 my-3">5</p>
                                    <p class="text-white small mb-3">Utworzone wydarzenia</p>
                                    <a href="#" class="btn btn-sm btn-outline-light mt-auto">Zobacz</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-white text-white h-100" style="background: rgba(255,255,255,0.05);">
                                <div class="card-body text-center d-flex flex-column">
                                    <h5 class="card-title">
                                        <i class="fas fa-tasks text-white me-2"></i>Ulubione
                                    </h5>
                                    <p class="h2 my-3">3</p>
                                    <p class="text-white small mb-3">Obserwowane wydarzenia</p>
                                    <a href="#" class="btn btn-sm btn-outline-light mt-auto">Zobacz</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: white;">
                        <div class="card-header d-flex justify-content-between align-items-center"
                             style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Ostatnie aktywności</h5>
                            <a href="#" class="btn btn-sm btn-outline-light">Więcej</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.05);">
                                    <div class="d-flex align-items-center">
                                        <div class=" rounded-circle p-2 me-3" style="background: rgba(255,255,255,0.05);">
                                            <i class="fas fa-sign-in-alt text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-white">Zalogowano pomyślnie</h6>
                                            <p class="mb-0 small text-white">System Faily</p>
                                        </div>
                                    </div>
                                    <small class="text-white">5 minut temu</small>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.05);">
                                    <div class="d-flex align-items-center">
                                        <div class=" rounded-circle p-2 me-3" style="background: rgba(255,255,255,0.05);">
                                            <i class="fas fa-edit text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-white">Zaktualizowano profil</h6>
                                            <p class="mb-0 small text-white">Dane osobowe</p>
                                        </div>
                                    </div>
                                    <small class="text-white">2 dni temu</small>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: white;">
                        <div class="card-header" style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Szybkie akcje</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <a href="{{ url('/add_event') }}" class="btn btn-outline-light w-100 py-3">
                                        <i class="fas fa-plus me-2"></i> Dodaj wydarzenie
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
