<div class="col-md-3">
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>{{ __('messages.admin.adminMenu') }}</h5>
        </div>
        <div class="list-group list-group-flush">
            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-tachometer-alt me-2"></i>{{ __('messages.admin.dashboard') }}
            </a>
            <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-users me-2"></i>{{ __('messages.admin.users') }}
            </a>
            <a href="{{ route('admin.reports') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-flag me-2"></i>{{ __('messages.admin.reports') }}
            </a>
        </div>
    </div>
</div>
