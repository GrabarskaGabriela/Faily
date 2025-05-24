<div class="col-md-3">
    <div class="card shadow-sm fade-in-up">
        <div class="card-header text-color_2">
            <h5>{{ __('messages.eventlist.upcomingEventsLabel') }}</h5>
        </div>
        <div class="card-body">
            @foreach($upcomingEvents as $event)
                <div class="mb-3 d-flex align-items-center lift-card">
                    <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-color d-flex align-items-center">
                        @if($event->photos->count())
                            <img src="{{ asset('storage/' . $event->photos->first()->path) }}" class="rounded me-2" width="50" alt="{{ $event->title }}">
                        @else
                            <div class="bg-secondary rounded me-2" style="width:50px; height:50px;"></div>
                        @endif
                        <div>
                            <h6 class="mb-0">{{ $event->title }}</h6>
                            <small class="text-color">{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
