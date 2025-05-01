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
                <div class="profile-header position-relative mb-4">
                </div>
                <div class="text-center text-white">
                    <div class="position-relative d-inline-block">
                        @if(Auth::user()->photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->photo_path) }}"
                                 class="rounded-circle profile-pic" alt="Profile photo" width="300" height="300">
                        @else
                            <img src="{{ asset('images/includes/default_avatar.png') }}"
                                 class="rounded-circle profile-pic" alt="Profile photo" width="300" height="300">
                        @endif
                    </div>
                    <h3 class="mt-3 mb-1">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                    <p class="mb-3">{{ Auth::user()->age }} lat</p>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-black shadow-sm">
                    <div class="card-body p-0 text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                        <div class="row g-0">
                            <div class="col-lg-9">
                                <div class="p-4">
                                    <div class="mb-4">
                                        <h5 class="mb-4">{{ __('messages.account.info') }}</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('messages.account.fullName') }}</label>
                                                <p class="border rounded p-2 bg-light text-black">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} ps. '{{ Auth::user()->name }}'</p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('messages.account.email') }}</label>
                                                <p class="border rounded p-2 bg-light text-black">{{ Auth::user()->email }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('messages.account.phone') }}</label>
                                                <p class="border rounded p-2 bg-light text-black">{{ Auth::user()->phone }}</p>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">{{ __('messages.account.aboutMe') }}</label>
                                                <div class="border rounded p-2 bg-light text-black">{{ Auth::user()->description }}</div>
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
</div>
</div>
@include('includes.footer')
</body>
</html>
