<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.account') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')

<div>
    <div class="container py-5">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="profile-header position-relative mb-4"></div>

                <div class="text-center text-color fade-in-up">
                    <div class="position-relative d-inline-block">
                        @if(Auth::user()->photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->photo_path) }}"
                                 class="rounded-circle profile-pic" alt="Profile photo" width="350" height="350">
                        @else
                            <img src="{{ asset('images/includes/default-avatar.png') }}"
                                 class="rounded-circle profile-pic" alt="Profile photo" width="350" height="350">
                        @endif
                    </div>
                    <h3 class="mt-3 mb-1">{{ Auth::user()->name }}</h3>
                    <p class="mb-3">{{ Auth::user()->age }} {{ __('messages.account.years') }}</p>

                    <div class="row">
                        <div class="col">
                            <div class="about-me-highlight p-3 rounded-4 shadow-lg fade-in-up delay-1">
                                <h3 class="text-color mb-3">{{ __('messages.account.aboutMe') }}</h3>
                                <p class="about-me-text lead m-0">„{{ Auth::user()->description ?: '-' }}”</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div class="card shadow-sm mb-5 fade-in-up delay-2">
                        <div class="card-header bg-white">
                            <h4 class="mb-0">{{ __('messages.account.info') }}</h4>
                        </div>
                        <div class="card-body text-color">
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">{{ __('messages.account.fullName') }}:</div>
                                <div class="col-md-8">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                    <small class="text-muted">ps. '{{ Auth::user()->name }}'</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">{{ __('messages.account.email') }}:</div>
                                <div class="col-md-8">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">{{ __('messages.account.phone') }}:</div>
                                <div class="col-md-8">{{ Auth::user()->phone }}</div>
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
