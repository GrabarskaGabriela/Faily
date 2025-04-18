<x-app-layout>
    <div class="container mt-5 text-white">
        <div class="row">
            <!-- Lewa kolumna - filtry i kategorie -->
            <div class="col-md-3">
                <div class="card custom-card-bg mb-4">
                    <div class="card-header">
                        <h4>Filtry</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('events.feed') }}" method="GET">
                            <div class="mb-3">
                                <label for="search" class="form-label">Szukaj</label>
                                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}">
                            </div>

                            <div class="mb-3">
                                <label for="date_from" class="form-label">Data od</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            </div>

                            <div class="mb-3">
                                <label for="date_to" class="form-label">Data do</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="has_ride_sharing" name="has_ride_sharing" value="1" {{ request('has_ride_sharing') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_ride_sharing">Tylko z przejazdem</label>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="has_available_spots" name="has_available_spots" value="1" {{ request('has_available_spots') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_available_spots">Tylko z wolnymi miejscami</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Filtruj</button>
                        </form>
                    </div>
                </div>

                <div class="card custom-card-bg">
                    <div class="card-header">
                        <h4>Szybkie linki</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('events.create') }}" class="btn btn-success mb-2">Utwórz nowe wydarzenie</a>
                            <a href="{{ route('my.events') }}" class="btn btn-info mb-2">Moje wydarzenia</a>
                            <a href="{{ route('events.index') }}" class="btn btn-secondary">Wszystkie wydarzenia</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Środkowa kolumna - wydarzenia -->
            <div class="col-md-6">
                <div class="mb-4">
                    <h2>Aktualności</h2>
                    <p class="text-muted">Nadchodzące wydarzenia od Twoich znajomych i nie tylko</p>
                </div>

                @forelse($events as $event)
                    <div class="card custom-card-bg mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="{{ $event->user->avatar }}" class="rounded-circle me-2" width="40" alt="{{ $event->user->name }}">
                                <div>
                                    <h5 class="mb-0">{{ $event->user->name }}</h5>
                                    <small class="text-muted">{{ $event->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                        @if($event->photos->count() > 0)
                            <a href="{{ route('events.show', $event) }}">
                                <img src="{{ asset('storage/' . $event->photos->first()->path) }}" class="card-img-top" alt="{{ $event->title }}">
                            </a>
                        @endif

                        <div class="card-body">
                            <h4 class="card-title">{{ $event->title }}</h4>
                            <p class="card-text">
                                <i class="bi bi-geo-alt"></i> {{ $event->location_name }}<br>
                                <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y H:i') }}
                            </p>

                            <p class="card-text">{{ Str::limit($event->description, 200) }}</p>

                            @php
                                $totalAttendees = $event->acceptedAttendees()->sum('attendees_count');
                                $availableSpots = max(0, $event->people_count - $totalAttendees);
                            @endphp

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="badge bg-info">{{ $totalAttendees }} / {{ $event->people_count }} uczestników</span>

                                    @if($event->has_ride_sharing)
                                        <span class="badge bg-success">Z przejazdem</span>
                                    @endif

                                    @if($availableSpots > 0)
                                        <span class="badge bg-warning">{{ $availableSpots }} wolnych miejsc</span>
                                    @else
                                        <span class="badge bg-danger">Brak miejsc</span>
                                    @endif
                                </div>
                            </div>

                            @auth
                                @php
                                    $attendee = $event->attendees()->where('user_id', Auth::id())->first();
                                @endphp

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-primary">Szczegóły</a>

                                    @if(!$attendee && $availableSpots > 0 && Auth::id() !== $event->user_id)
                                        <a href="{{ route('events.attendees.create', $event) }}" class="btn btn-success">Zapisz się</a>
                                    @elseif($attendee)
                                        @if($attendee->status == 'pending')
                                            <span class="badge bg-warning align-self-center">Oczekuje na potwierdzenie</span>
                                        @elseif($attendee->status == 'accepted')
                                            <span class="badge bg-success align-self-center">Zapisany</span>
                                        @elseif($attendee->status == 'rejected')
                                            <span class="badge bg-danger align-self-center">Odrzucony</span>
                                        @endif
                                    @elseif(Auth::id() === $event->user_id)
                                        <span class="badge bg-info align-self-center">Twoje wydarzenie</span>
                                    @endif
                                </div>
                            @else
                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary">Szczegóły</a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        <p>Brak wydarzeń do wyświetlenia. Bądź pierwszy i utwórz nowe wydarzenie!</p>
                        <a href="{{ route('events.create') }}" class="btn btn-success mt-2">Utwórz wydarzenie</a>
                    </div>
                @endforelse

                <div class="d-flex justify-content-center mt-4">
                    {{ $events->links() }}
                </div>
            </div>

            <!-- Prawa kolumna - popularne wydarzenia i sugestie -->
            <div class="col-md-3">
                <div class="card custom-card-bg mb-4">
                    <div class="card-header">
                        <h4>Popularne wydarzenia</h4>
                    </div>
                    <div class="card-body">
                        @foreach($popularEvents as $event)
                            <div class="mb-3">
                                <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-light">
                                    <div class="d-flex align-items-center">
                                        @if($event->photos->count() > 0)
                                            <img src="{{ asset('storage/' . $event->photos->first()->path) }}" class="rounded me-2" width="50" alt="{{ $event->title }}">
                                        @else
                                            <div class="bg-secondary rounded me-2" style="width:50px; height:50px;"></div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $event->title }}</h6>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card custom-card-bg">
                    <div class="card-header">
                        <h4>Nadchodzące wkrótce</h4>
                    </div>
                    <div class="card-body">
                        @foreach($upcomingEvents as $event)
                            <div class="mb-3">
                                <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-light">
                                    <div class="d-flex align-items-center">
                                        @if($event->photos->count() > 0)
                                            <img src="{{ asset('storage/' . $event->photos->first()->path) }}" class="rounded me-2" width="50" alt="{{ $event->title }}">
                                        @else
                                            <div class="bg-secondary rounded me-2" style="width:50px; height:50px;"></div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $event->title }}</h6>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
