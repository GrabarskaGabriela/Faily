<section class="mb-5" >
    <header class="mb-4" >
        <h2 class="h4 text-white" >
            {{ __('Usuń konto') }}
        </h2>

        <p class="text-white small mt-2">
            {{ __('Wraz z usunięciem konta wszystkie powiązane dane i zasoby zostaną bezpowrotnie usunięte.') }}
        </p>
    </header>

    <button type="button"
            class="btn btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#confirmUserDeletionModal">
        {{ __('Usuń konto') }}
    </button>

    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-content text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="confirmUserDeletionLabel">
                            {{ __('Czy na pewno chcesz usunąć swoje konto?') }}
                        </h5>
                        <button type="button" class="btn-close white-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                    </div>
                    <style>
                        .btn-close.white-close {
                            filter: invert(1);
                        }
                    </style>
                    <div class="modal-body">
                        <p class="text-white small">
                            {{ __('Wraz z usunięciem konta wszystkie powiązane dane i zasoby zostaną bezpowrotnie usunięte. Upewnij się, że pobrałeś wszystkie informacje, które chcesz zachować, przed przystąpieniem do usuwania konta.') }}
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label visually-hidden">{{ __('Hasło') }}</label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control"
                                   placeholder="{{ __('Hasło') }}"
                                   required>
                            @if ($errors->userDeletion->get('password'))
                                <div class="text-danger small mt-1">
                                    {{ $errors->userDeletion->first('password') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Anuluj') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            {{ __('Usuń konto') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
