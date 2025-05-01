<div class="col-md-3 mb-4" >
    <div class="card shadow-sm border-dark" style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%);">
        <div class="card-header text-white">
            <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>  {{ __('messages.profilemenu.myAccount') }}</h5>
        </div>
        <div class="list-group list-group-flush">
            <a href="{{ route('profile.dashboard') }}" class="list-group-item list-group-item-action text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                <i class="fas fa-tachometer-alt me-2"></i>{{ __('messages.profilemenu.dashboard') }}
            </a>
            <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                <i class="fas fa-user-edit me-2"></i>{{ __('messages.profilemenu.profile') }}
            </a>
            <a href="{{ route('my_events') }}" class="list-group-item list-group-item-action text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                <i class="fas fa-home me-2"></i>{{ __('messages.profilemenu.myEvents') }}
            </a>
        </div>
    </div>
</div>
