<div class="col-md-3 mb-4" >
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>{{ __('messages.profilemenu.myAccount') }}</h5>
        </div>
        <div class="list-group list-group-flush">
            <a href="{{ route('profile.dashboard') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-tachometer-alt me-2"></i>{{ __('messages.profilemenu.dashboard') }}
            </a>
            <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-user-edit me-2"></i>{{ __('messages.profilemenu.profile') }}
            </a>
            <a href="{{ route('my_events') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-home me-2"></i>{{ __('messages.profilemenu.myEvents') }}
            </a>
        </div>
    </div>
</div>
