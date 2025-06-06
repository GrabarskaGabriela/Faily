<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.profileEdit') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="row">
        @include('includes.profile_menu')

        <div class="col-md-9 text-white">

            <div class="card shadow-sm mb-4 ">
                <div class="card-header text-center">
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2 text-white"></i>{{ __('messages.profileedit.personalData') }}</h4>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div class="w-100" style="max-width: 600px;">
                        @include('profile.partials.update-profile-information-form', [
                            'user' => $user,
                            'mustVerifyEmail' => $mustVerifyEmail ?? false,
                            'verified' => $verified ?? false,
                        ])
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-key me-2 text-white"></i>{{ __('messages.profileedit.passwordChange') }}</h4>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div class="w-100" style="max-width: 600px;">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h4 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-white"></i>{{ __('messages.profileedit.deleteAccount') }}</h4>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div class="w-100" style="max-width: 600px;">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ __('messages.profileedit.deleteInfo') }}
                        </div>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

@include('includes.footer')
</body>
</html>
