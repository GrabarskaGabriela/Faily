<button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#reportUserModal{{ $userId }}" type="button">
    <i class="fas fa-flag me-2 text-danger"></i>
    {{ __('messages.reportusermodal.reportUser') }}
</button>

<div class="modal fade" id="reportUserModal{{ $userId }}" tabindex="-1" aria-labelledby="reportUserModalLabel{{ $userId }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('users.report', $userId) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="reportUserModalLabel{{ $userId }}">{{ __('messages.reportusermodal.reportUser') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('messages.reportusermodal.desc') }} <strong>{{ $userName }}</strong></p>

                    <div class="form-group mb-3">
                        <label for="reason{{ $userId }}" class="form-label">{{ __('messages.reportusermodal.reason') }}</label>
                        <textarea id="reason{{ $userId }}" name="reason" class="form-control" rows="4" required minlength="10"></textarea>
                        <div class="form-text text-color">{{ __('messages.reportusermodal.char') }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient-secondary" data-bs-dismiss="modal">{{ __('messages.reportusermodal.reject') }}</button>
                    <button type="submit" class="btn btn-gradient-danger">{{ __('messages.reportusermodal.report') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
