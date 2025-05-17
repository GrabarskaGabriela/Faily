<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.profileDashboard') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="row">
        @include('includes.profile_menu')

        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>{{ __('messages.profiledashboard.summary') }}</h4>
                    <span class="badge">{{ __('messages.profiledashboard.lastUpdate') }} {{ now()->format('d.m.Y H:i') }}</span>
                </div>

                <div class="card-body">
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4"></i>
                        <div>
                            <h5 class="alert-heading mb-1">{{ __('messages.profiledashboard.welcome') }} {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</h5>
                            <p class="mb-0">{{ __('messages.profiledashboard.loggedInInfo') }}</p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header" >
                            <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>{{ __('messages.profiledashboard.quickActions') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <a href="{{ url('/add_event') }}" class="btn btn-gradient-secondary w-100 py-3">
                                        <i class="fas fa-plus me-2"></i> {{ __('messages.profiledashboard.addEvent') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ url('/account') }}" class="btn btn-gradient-secondary w-100 py-3">
                                        <i class="fas me-2"></i> {{ __('messages.navigation.goToAccount') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ url('/help') }}" class="btn btn-gradient-secondary w-100 py-3">
                                        <i class="fas me-2"></i> {{ __('messages.help.text') }}
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
