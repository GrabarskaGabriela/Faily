<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.profileEditPhoto') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')
<main class="container flex-grow-1 my-5">
    <div>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-center mb-4">{{ __('messages.profileeditphoto.photoUpdate') }}</h3>

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    @if($user->photo_path)
                                        <img src="{{ asset('storage/' . $user->photo_path) }}" class="rounded-circle profile-pic img-fluid" alt="{{ __('messages.profileedit.currentPhoto') }}">
                                    @else
                                        <img src="{{ asset('images/default-avatar.png') }}" class="rounded-circle profile-pic img-fluid" alt="{{ __('messages.profileedit.defaultPhoto') }}">
                                    @endif
                                </div>
                            </div>

                            <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="photo" class="form-label">{{ __('messages.profileedit.newPhoto') }}</label>
                                    <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo" name="photo" accept="image/*">
                                    @error('photo')
                                    <div class="invalid-feedback">{{ __('messages.profileedit.photoSizeError') }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">{{ __('messages.profileedit.savePhoto') }}</button>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">{{ __('messages.profileedit.passwordChange') }}</a>
                                </div>
                            </form>

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
