<section class="mb-5" >
    <header class="mb-4" >
        <h2 class="h4 text-white" >
            {{ __('messages.profilepartialsdelete.deleteAccount') }}
        </h2>

        <p class="text-white small mt-2">
            {{ __('messages.profilepartialsdelete.deleteAccountDataWarning') }}
        </p>
    </header>

    <button type="button"
            class="btn btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#confirmUserDeletionModal">
        {{ __('messages.profilepartialsdelete.deleteAccount') }}
    </button>

    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-content text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="confirmUserDeletionLabel">
                            {{ __('messages.profilepartialsdelete.confirmDeleteAccount') }}
                        </h5>
                        <button type="button" class="btn-close white-close" data-bs-dismiss="modal" aria-label=" {{ __('messages.profilepartialsdelete.closeButton') }}"></button>
                    </div>
                    <style>
                        .btn-close.white-close {
                            filter: invert(1);
                        }
                    </style>
                    <div class="modal-body">
                        <p class="text-white small">
                            {{ __('messages.profilepartialsdelete.deleteAccountFinalWarning') }}
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label visually-hidden"> {{ __('messages.profilepartialsdelete.password') }}</label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control"
                                   placeholder=" {{ __('messages.profilepartialsdelete.password') }}"
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
                            {{ __('messages.profilepartialsdelete.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            {{ __('messages.profilepartialsdelete.deleteAccount') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
