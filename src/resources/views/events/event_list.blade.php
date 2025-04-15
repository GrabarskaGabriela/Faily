<x-app-layout>
    <div class="container mt-4">
        <h1>Moje wydarzenia</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-12">
                <a href="{{ route('events.create') }}" class="btn btn-primary">Dodaj nowe wydarzenie</a>
            </div>
        </div>

        <div class="row">
            @forelse ($events as $event)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-dark text-white">
                            <h5 class="card-title mb-0">{{ $event->title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    @if(isset($event->photos) && count($event->photos) > 0)
                                        <img src="{{ asset('storage/' . $event->photos[0]->path) }}"
                                             alt="Zdjęcie wydarzenia"
                                             class="img-fluid rounded mb-2 event-thumbnail">
                                    @else
                                        <div class="bg-light text-center py-4 rounded">
                                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Brak zdjęcia</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y H:i') }}</p>
                            <p><strong>Lokalizacja:</strong> {{ $event->location_name }}</p>

                            <!-- Dodaj informację o przejazdach -->
                            @if($event->has_ride_sharing)
                                <p>
                                    <span class="badge bg-success">
                                        <i class="bi bi-car-front"></i> Dostępne przejazdy
                                    </span>
                                </p>
                            @endif

                            <p class="text-truncate">{{ Str::limit($event->description, 100) }}</p>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary">Zobacz szczegóły</a>
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-secondary">Edytuj</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Nie utworzyłeś jeszcze żadnych wydarzeń.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $events->links() }}
        </div>
    </div>

    <style>
        .event-thumbnail {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
    </style>
</x-app-layout>
