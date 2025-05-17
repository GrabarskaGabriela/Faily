<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.title.verifyEmail') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="card shadow-sm mx-auto border-black text-color" style="max-width: 500px;">
        <div class="card-body shadow-sm">
            <h3 class="card-title text-center mb-4">{{ __('auth.authverifyemail.titleLabel') }}</h3>

            <div class="alert alert-info mb-4">
                {{ __('auth.authverifyemail.instructionText') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success mb-4">
                    {{ __('auth.authverifyemail.verificationLinkSentMessage') }}
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mt-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-gradient text-color_2">
                        {{ __('auth.authverifyemail.resendButtonLabel') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-gradient btn-link text-decoration-none text-color_2">
                        {{ __('auth.authverifyemail.logoutButtonLabel') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
