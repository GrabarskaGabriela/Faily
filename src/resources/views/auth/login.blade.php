<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.login') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('includes.navbar')
<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-black">
                <div class="card-body" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    <h3 class="card-title text-center text-white mb-4">{{ __('auth.authlogin.login') }}</h3>

                    @if (session('status'))
                        <div class="alert alert-info mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label text-white">{{ __('auth.authlogin.emailLabel') }}</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('auth.authlogin.emailPlaceholder') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-white">{{ __('auth.authlogin.passwordLabel') }}</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('auth.authlogin.passwordPlaceholder') }}" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label text-white" for="remember">{{ __('auth.authlogin.rememberMeLabel') }}</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="btn text-white" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%); border: none;">{{ __('auth.authlogin.forgotPasswordLink') }}</a>
                            @else
                                <a href="#" class="btn btn-outline-primary">{{ __('auth.authlogin.forgotPasswordLink') }}</a>
                            @endif
                            <button type="submit" class="btn border-black text-white" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">{{ __('auth.authlogin.loginButton') }}</button>
                        </div>
                    </form>

                    <hr>

                    <div class="d-grid gap-2 text-center">
                        <p class="mb-3 text-white">{{ __('auth.authlogin.noAccountText') }}</p>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn border-black text-white" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">{{ __('auth.authlogin.registerLink') }}</a>
                        @else
                            <a href="#" class="btn border-black text-white" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">{{ __('auth.authlogin.registerLink') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('includes.footer')
</body>
</html>
