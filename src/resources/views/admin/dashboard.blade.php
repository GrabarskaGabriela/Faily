<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.adminDashboard') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="row">
        @include('includes.admin_menu')
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>{{ __('messages.admin.dashboard') }}</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4"></i>
                        <div>
                            <h5 class="alert-heading mb-1">{{ __('messages.admin.welcomeAdmin') }}</h5>
                            <p class="mb-0">{{ __('messages.admin.accessInfo') }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-color_2" style="background-color: #651fff">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">{{ __('messages.admin.users') }}</h6>
                                            <h2 class="mb-0">{{ $stats['users_count'] }}</h2>
                                        </div>
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-color_2"  style="background-color: #7c40ff">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">{{ __('messages.admin.blocked') }}</h6>
                                            <h2 class="mb-0">{{ $stats['banned_users'] }}</h2>
                                        </div>
                                        <i class="fas fa-user-slash fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-color_2"  style="background-color: #9b6dff">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">{{ __('messages.admin.reports') }}</h6>
                                            <h2 class="mb-0">{{ $stats['pending_reports'] }}</h2>
                                        </div>
                                        <i class="fas fa-flag fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-sm text-color_2">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>{{ __('messages.admin.menageUsers') }}</h5>
                                </div>
                                <div class="card-body">
                                    <p>{{ __('messages.admin.actionInfo') }}</p>
                                    <a href="{{ route('admin.users') }}" class="btn btn-gradient w-100 text-color_2">{{ __('messages.admin.goToUsers') }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card shadow-sm text-color_2">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-flag me-2"></i>{{ __('messages.admin.reports') }}</h5>
                                </div>
                                <div class="card-body">
                                    <p>{{ __('messages.admin.menageUsersButton') }}</p>
                                    <a href="{{ route('admin.reports') }}" class="btn btn-gradient w-100 text-color_2">{{ __('messages.admin.goToReports') }}</a>
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
