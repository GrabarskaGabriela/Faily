<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.events') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')

<main class="container py-5 text-color">
    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow-sm mb-4 border-black">
                <div class="card-header text-color">
                    <h5>{{ __('messages.eventlist.filterTitle') }}</h5>
                </div>
                <div class="card-body shadow-sm text-color">
                    <form action="{{ route('events.feed') }}" method="GET">
                        <div class="mb-3">
                            <label for="search" class="form-label">{{ __('messages.eventlist.searchButton') }}</label>
                            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}">
                        </div>
                        <div class="mb-3">
                            <label for="date_from" class="form-label">{{ __('messages.eventlist.startDateLabel') }}</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="mb-3">
                            <label for="date_to" class="form-label">{{ __('messages.eventlist.endDateLabel') }}</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="has_ride_sharing" name="has_ride_sharing" value="1" {{ request('has_ride_sharing') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_ride_sharing">{{ __('messages.eventlist.withTransportCheckbox') }}</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="has_available_spots" name="has_available_spots" value="1" {{ request('has_available_spots') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_available_spots">{{ __('messages.eventlist.withFreeSpotsCheckbox') }}</label>
                        </div>
                        <button type="submit" class="btn btn-gradient text-color">{{ __('messages.eventlist.applyFiltersButton') }}</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-black">
                <div class="card-header text-color">
                    <h5>{{ __('messages.eventlist.quickLinksLabel') }}</h5>
                </div>
                <div class="card-body d-grid gap-2 text-color">
                    <a href="{{ route('events.create') }}" class="btn text-color btn-gradient" >{{ __('messages.eventlist.createEventButton') }}</a>
                    <a href="{{ route('my_events') }}" class="btn text-color btn-gradient">{{ __('messages.eventlist.myEventsButton') }}</a>
                    <a href="{{ route('events.index') }}" class="btn text-color btn-gradient">{{ __('messages.eventlist.allEventsButton') }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="mb-4" >{{ __('messages.eventlist.newsSectionTitle') }}</h2>
            @forelse($events as $event)
                <div class="card shadow-sm mb-4 text-color">
                    <div class="card-header d-flex align-items-center gap-3">
                        @if($event->user->photo_path)
                            <img src="{{ asset('storage/' . $event->user->photo_path) }}"
                                 class="rounded-circle border border-2 border-light"
                                 alt="{{ __('messages.eventlist.profilePhotoLabel') }}" width="50" height="50" style="object-fit: cover;">
                        @else
                            <img src="{{ asset('images/includes/default-avatar.png') }}"
                                 class="rounded-circle border border-2 border-light"
                                 alt="{{ __('messages.eventlist.profilePhotoLabel') }}" width="50" height="50" style="object-fit: cover;">
                        @endif
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $event->user->name }}</h6>
                            <small class="text-light">{{ $event->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    @if($event->photos->count())
                        <a href="{{ route('events.show', $event) }}">
                            <img src="{{ asset('storage/' . $event->photos->first()->path) }}"
                                 class="card-img-top"
                                 alt="{{ $event->title }}">
                        </a>
                    @endif

                    <div class="card-body d-flex flex-column gap-3">
                        <div>
                            <h5 class="card-title mb-2">{{ $event->title }}</h5>
                            <p class="mb-1">
                                <i class="bi bi-geo-alt"></i>{{ __('messages.eventlist.locationLabel') }} {{ $event->location_name }}
                            </p>
                            <p class="mb-3">
                                <i class="bi bi-calendar"></i>{{ __('messages.eventlist.dateLabel') }} {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y H:i') }}
                            </p>
                            <p class="text-color">{{ __('messages.eventlist.eventDescriptionLabel') }}</p>
                            <p class="text-color">{{ Str::limit($event->description, 150) }}</p>
                        </div>

                        @php
                            $totalAttendees = $event->acceptedAttendees()->sum('attendees_count');
                            $availableSpots = max(0, $event->people_count - $totalAttendees);
                        @endphp

                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-info">{{ $totalAttendees }}/{{ $event->people_count }} {{ __('messages.eventlist.participantsCountLabel') }}</span>
                            @if($event->has_ride_sharing)
                                <span class="badge bg-success">{{ __('messages.eventlist.statusWithTransport') }}</span>
                            @endif
                            @if($availableSpots > 0)
                                <span class="badge bg-warning">{{ $availableSpots }} {{ __('messages.eventlist.statusFreeSpots') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('messages.eventlist.statusNoSpots') }}</span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ route('events.show', $event) }}"
                               class="btn btn-gradient text-color">
                                {{ __('messages.eventlist.detailsLabel') }}
                            </a>

                            @auth
                                @php
                                    $attendee = $event->attendees()->where('user_id', Auth::id())->first();
                                @endphp
                                @if(!$attendee && $availableSpots > 0 && Auth::id() !== $event->user_id)
                                    <a href="{{ route('events.attendees.create', $event) }}"
                                       class="btn btn-success"
                                       style="border-radius: 20px; padding: 5px 20px;">
                                        {{ __('messages.eventlist.signUpButton') }}
                                    </a>
                                @elseif($attendee)
                                    @if($attendee->status == 'pending')
                                        <span class="badge bg-warning align-self-center">{{ __('messages.eventlist.statusPending') }}</span>
                                    @elseif($attendee->status == 'accepted')
                                        <span class="badge bg-success align-self-center">{{ __('messages.eventlist.statusConfirmed') }}</span>
                                    @elseif($attendee->status == 'rejected')
                                        <span class="badge bg-danger align-self-center">{{ __('messages.eventlist.statusRejected') }}</span>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">
                    {{ __('messages.eventlist.noEventsMessage') }}<br>
                    <a href="{{ route('events.create') }}" class="btn btn-success mt-2">{{ __('messages.eventlist.addFirstEventButton') }}</a>
                </div>
            @endforelse


            <div class="d-flex justify-content-center mt-4">
                {{ $events->links() }}
            </div>
        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-black">
                <div class="card-header text-color">
                    <h5>{{ __('messages.eventlist.upcomingEventsLabel') }}</h5>
                </div>
                <div class="card-body">
                    @foreach($upcomingEvents as $event)
                        <div class="mb-3 d-flex align-items-center">
                            <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-light d-flex align-items-center">
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

    </div>
</main>

@include('includes.footer')
</body>
</html>
