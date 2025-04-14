<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - edycja zdjęcia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')
<div>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Aktualizacja zdjęcia profilowego</h3>

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if($user->photo_path)
                                    <img src="{{ asset('storage/' . $user->photo_path) }}" class="rounded-circle profile-pic img-fluid" alt="Aktualne zdjęcie profilowe">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" class="rounded-circle profile-pic img-fluid" alt="Domyślne zdjęcie profilowe">
                                @endif
                            </div>
                        </div>

                        <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="photo" class="form-label">Wybierz nowe zdjęcie</label>
                                <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo" name="photo" accept="image/*">
                                @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Zapisz zdjęcie</button>
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Anuluj</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
</body>
</html>
