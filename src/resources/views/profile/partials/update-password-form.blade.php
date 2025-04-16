<section class="mb-5">
    <header class="mb-4">
        <h2 class="h4 text-white">
            {{ __('Aktualizacja hasła') }}
        </h2>

        <p class=" small mt-2 text-white">
            {{ __('Dla zachowania bezpieczeństwa upewnij się, że Twoje konto chronione jest silnym, losowym hasłem.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3 text-white">
            <label for="update_password_current_password" class="form-label">
                {{ __('Obecne hasło') }}
            </label>
            <input type="password"
                   class="form-control"
                   id="update_password_current_password"
                   name="current_password"
                   autocomplete="current-password"
                   required>
            @if ($errors->updatePassword->get('current_password'))
                <div class="text-danger small mt-1">
                    {{ $errors->updatePassword->first('current_password') }}
                </div>
            @endif
        </div>

        <div class="mb-3 text-white">
            <label for="update_password_password" class="form-label">
                {{ __('Nowe hasło') }}
            </label>
            <input type="password"
                   class="form-control"
                   id="update_password_password"
                   name="password"
                   autocomplete="new-password"
                   required>
            @if ($errors->updatePassword->get('password'))
                <div class="text-danger small mt-1">
                    {{ $errors->updatePassword->first('password') }}
                </div>
            @endif
        </div>

        <div class="mb-4 text-white">
            <label for="update_password_password_confirmation" class="form-label">
                {{ __('Potwierdź hasło') }}
            </label>
            <input type="password"
                   class="form-control"
                   id="update_password_password_confirmation"
                   name="password_confirmation"
                   autocomplete="new-password"
                   required>
            @if ($errors->updatePassword->get('password_confirmation'))
                <div class="text-danger small mt-1">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                {{ __('Zapisz') }}
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-success small">
                    {{ __('Zapisane.') }}
                </span>
            @endif
        </div>
    </form>
</section>
