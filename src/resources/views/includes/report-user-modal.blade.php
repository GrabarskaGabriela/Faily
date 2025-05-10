<div class="dropdown-item" data-bs-toggle="modal" data-bs-target="#reportUserModal{{ $userId }}">
    <i class="fas fa-flag me-2 text-danger"></i>Zgłoś użytkownika
</div>

<div class="modal fade" id="reportUserModal{{ $userId }}" tabindex="-1" aria-labelledby="reportUserModalLabel{{ $userId }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('users.report', $userId) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="reportUserModalLabel{{ $userId }}">Zgłoś użytkownika</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Opisz powód zgłoszenia użytkownika: <strong>{{ $userName }}</strong></p>

                    <div class="form-group mb-3">
                        <label for="reason{{ $userId }}" class="form-label">Powód zgłoszenia</label>
                        <textarea id="reason{{ $userId }}" name="reason" class="form-control" rows="4" required minlength="10"></textarea>
                        <div class="form-text">Minimum 10 znaków</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-danger">Zgłoś</button>
                </div>
            </form>
        </div>
    </div>
</div>
