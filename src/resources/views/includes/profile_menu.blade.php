<!-- Lewa kolumna - menu boczne -->
<div class="col-md-3 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Moje konto</h5>
        </div>
        <div class="list-group list-group-flush">
            <a href="{{ route('profile.dashboard') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-tachometer-alt me-2"></i>Podsumowanie
            </a>
            <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-user-edit me-2"></i>Profil
            </a>
            <a href="{{ route('password.confirm') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-key me-2"></i>Zmiana hasła
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                <i class="fas fa-bell me-2"></i>Wiadomości
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                <i class="fas fa-home me-2"></i>Moje wydarzenia
            </a>
        </div>
    </div>
</div>
