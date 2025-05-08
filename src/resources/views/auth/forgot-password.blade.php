<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.title.forgotPassword') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="card shadow-sm mx-auto border-dark" style="max-width: 400px;">
        <div class="card-body shadow-sm">
            <h3 class="card-title text-center mb-4 text-color">{{ __('auth.authforgotpassword.titleLabel') }}</h3>

            <div class="mb-4 text-color">
                {{ __('auth.authforgotpassword.instructionText') }}
            </div>

            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ __('auth.authforgotpassword.emailSentMessage') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label text-color">{{ __('auth.authforgotpassword.emailInputLabel') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="{{ __('auth.authforgotpassword.emailInputLabel') }}"
                           required autofocus>
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-gradient text-color">
                        {{ __('auth.authforgotpassword.submitButton') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
