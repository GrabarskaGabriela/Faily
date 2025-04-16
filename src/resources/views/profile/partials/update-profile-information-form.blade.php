<section class="mb-5">
    <header class="mb-4">
        <h2 class="h4 text-white">
            {{ __('Informacje profilowe') }}
        </h2>
        <p class="text-white small">
            {{ __("Zaktualizuj informacje profilowe oraz adres e-mail swojego konta.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mb-3 text-white">
            <label for="name" class="form-label">{{ __('Nazwa użytkownika') }}</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @if ($errors->get('name'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="mb-3 text-white">
            <label for="first_name" class="form-label">{{ __('Imię') }}</label>
            <input type="text" class="form-control" id="first_name" name="first_name"
                   value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="first_name">
            @if ($errors->get('first_name'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('first_name') }}
                </div>
            @endif
        </div>

        <div class="mb-3 text-white">
            <label for="last_name" class="form-label">{{ __('Nazwisko') }}</label>
            <input type="text" class="form-control" id="last_name" name="last_name"
                   value="{{ old('last_name', $user->last_name) }}" required autofocus autocomplete="last_name">
            @if ($errors->get('last_name'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('last_name') }}
                </div>
            @endif
        </div>


        <div class="mb-3 text-white">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="{{ old('email', $user->email) }}" required autocomplete="username">
            @if ($errors->get('email'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('email') }}
                </div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 small text-dark">
                    {{ __('Twój adres e-mail nie został zweryfikowany.') }}

                    <button form="send-verification" class="btn btn-link p-0 align-baseline text-decoration-underline">
                        {{ __('Kliknij tutaj, aby ponownie wysłać wiadomość weryfikacyjną na twój adres e-mail.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="text-success mt-1">
                            {{ __('Na Twój adres e-mail został wysłany nowy link weryfikacyjny.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="mb-3 text-white">
            <label for="avatar" class="form-label">{{ __('Avatar') }}</label>
            @if($user->avatar)
                <div class="mb-2">
                    <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle" width="80" height="80">
                </div>
            @endif
            <input type="file" class="form-control" id="avatar" name="avatar">
            @if ($errors->get('avatar'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('avatar') }}
                </div>
            @endif
        </div>

        <div class="mb-3 text-white">
            <label for="preferred_language" class="form-label">{{ __('Preferowany język') }}</label>
            <select class="form-select" id="preferred_language" name="preferred_language">
                <option value="pl" {{ $user->language == 'pl' ? 'selected' : '' }}>Polski</option>
                <option value="en" {{ $user->language == 'en' ? 'selected' : '' }}>English</option>
                <option value="de" {{ $user->language == 'de' ? 'selected' : '' }}>Deutsch</option>
                <option value="uk" {{ $user->language == 'uk' ? 'selected' : '' }}>Українська</option>

            </select>
            @if ($errors->get('preferred_language'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('preferred_language') }}
                </div>
            @endif
        </div>

        <div class="mb-4 text-white">
            <label for="theme" class="form-label">{{ __('Motyw') }}</label>
            <select class="form-select" id="theme" name="theme">
                <option value="light" {{ $user->theme == 'light' ? 'selected' : '' }}>Light</option>
                <option value="dark" {{ $user->theme == 'dark' ? 'selected' : '' }}>Dark</option>
            </select>
            @if ($errors->get('theme'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('theme') }}
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Zapisz') }}</button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
