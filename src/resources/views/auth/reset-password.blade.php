<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.title.resetPassword') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="card shadow-sm mx-auto border-dark" style="max-width: 500px;">
        <div class="card-body" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);" >
            <h3 class="card-title text-center mb-4 text-white">{{ __('auth.authresetpassword.titleLabel') }}</h3>

            @if ($errors->any())
                <div class="alert alert-danger text-white">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3">
                    <label for="email" class="form-label text-white">{{ __('auth.authresetpassword.emailLabel') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="email">
                    @error('email')
                    <div class="invalid-feedback text-white">
                        {{ __('auth.authresetpassword.userNotFoundMessage') }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-white">{{ __('auth.authresetpassword.newPasswordLabel') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
                    @error('password')
                    <div class="invalid-feedback text-white">
                        {{ __('auth.authresetpassword.passwordMismatchmessage') }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label text-white">{{ __('auth.authresetpassword.confirmPasswordLabel') }}</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn text-white border-dark mt-2" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">
                        {{ __('auth.authresetpassword.submitButton') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
