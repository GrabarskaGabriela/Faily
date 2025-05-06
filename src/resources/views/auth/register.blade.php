<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.register') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('includes.navbar')

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);" >
                    <h3 class="card-title text-center mb-4 text-color">{{ __('auth.authregister.titleLabel') }}</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label text-color">{{ __('auth.authregister.usernameLabel') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __('auth.authregister.usernamePlaceholder') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label text-color">{{ __('auth.authregister.emailLabel') }}</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('auth.authregister.emailPlaceholder') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label text-color">{{ __('auth.authregister.passwordLabel') }}</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('auth.authregister.passwordPlaceholder') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label text-color">{{ __('auth.authregister.confirmPasswordLabel') }}</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{ __('auth.authregister.confirmPasswordPlaceholder') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label text-color">{{ __('auth.authregister.firstNameLabel') }}</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="{{ __('auth.authregister.firstNamePlaceholder') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label text-color">{{ __('auth.authregister.lastNameLabel') }}</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="{{ __('auth.authregister.lastNamePlaceholder') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="age" class="form-label text-color">{{ __('auth.authregister.ageLabel') }}</label>
                                <input type="number" class="form-control" id="age" name="age" value="{{ old('age') }}" placeholder="{{ __('auth.authregister.agePlaceholder') }}">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="phone" class="form-label text-color">{{ __('auth.authregister.phoneLabel') }}</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="{{ __('auth.authregister.phonePlaceholder') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label text-color">{{ __('auth.authregister.bioLabel') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="{{ __('auth.authregister.bioPlaceholder') }}">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <input type="file" class="d-none" id="eventPhoto" name="photo" onchange="updateFileName()">
                            <label for="eventPhoto" class="btn text-color btn-gradient mt-2">
                                {{ __('auth.authregister.profilePhotoLabel') }}
                            </label>
                            <div id="fileName" class="mt-2 text-color small">{{ __('auth.authregister.fileNotChoosen') }}</div>
                        </div>


                        <div class="d-grid gap-2">
                            <button type="submit" class="btn text-color btn-gradient">{{ __('auth.authregister.registerButton') }}</button>
                        </div>
                    </form>

                    <hr>

                    <div class="text-center">
                        <p class="mb-3 text-color">{{ __('auth.authregister.alreadyHaveAccountText') }}</p>
                        <a href="{{ route('login') }}" class="btn btn-gradient text-color" >{{ __('auth.authregister.loginLink') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
