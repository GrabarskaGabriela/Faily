<x-app-layout>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h2>{{ $event->title }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Data wydarzenia:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y H:i') }}</p>
                                <p><strong>Lokalizacja:</strong> {{ $event->location_name }}</p>
                                <p><strong>Liczba uczestników:</strong> {{ $event->people_count }}</p>
                                @if($event->has_ride_sharing)
                                    <p><strong>Dostępne współdzielenie przejazdów:</strong> Tak</p>
                                @else
                                    <p><strong>Dostępne współdzielenie przejazdów:</strong> Nie</p>
                                @endif
                                <hr>
                                <h4>Opis wydarzenia</h4>
                                <p>{{ $event->description }}</p>
                            </div>
                            <div class="col-md-6">
                                <div id="map" style="height: 300px; width: 100%; margin-bottom: 15px;"></div>

                                @if(isset($event->photos) && count($event->photos) > 0)
                                    <h4>Zdjęcia</h4>
                                    <div class="row g-2">
                                        @foreach($event->photos as $photo)
                                            <div class="col-md-6 col-6">
                                                <img src="{{ asset('storage/' . $photo->path) }}" alt="Zdjęcie wydarzenia" class="img-fluid rounded">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('events.index') }}" class="btn btn-secondary">Powrót do listy</a>

                            @if(auth()->id() == $event->user_id)
                                <div>
                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary">Edytuj</a>
                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć to wydarzenie?')">Usuń</button>
                                    </form>

                                    @if($event->has_ride_sharing)
                                        <hr class="my-4">
                                        <h3 class="mb-3">Dostępne przejazdy</h3>

                                        @if($event->rides && $event->rides->count() > 0)
                                            <div class="row">
                                                @foreach($event->rides as $ride)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card">
                                                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                                <h5 class="mb-0">Przejazd {{ $ride->id }}</h5>
                                                                <span class="badge bg-success">{{ $ride->avalible_seats }} miejsc</span>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Pojazd:</strong> {{ $ride->vehicle_description }}</p>
                                                                <p><strong>Kierowca:</strong> {{ $ride->driver->name }}</p>
                                                                <p><strong>Miejsce spotkania:</strong> {{ $ride->meeting_location_name }}</p>

                                                                <!-- Mapa miejsca spotkania -->
                                                                <div id="meeting-map-{{ $ride->id }}" style="height: 200px; width: 100%; margin-bottom: 15px;"></div>

                                                                @if(auth()->id() != $ride->driver_id)
                                                                    <a href="#" class="btn btn-success btn-sm w-100">Dołącz do przejazdu</a>
                                                                @else
                                                                    <div class="btn-group w-100">
                                                                        <a href="{{ route('rides.edit', $ride->id) }}" class="btn btn-primary btn-sm">Edytuj</a>
                                                                        <form action="{{ route('rides.destroy', $ride->id) }}" method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno chcesz usunąć ten przejazd?')">Usuń</button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                Brak zdefiniowanych przejazdów dla tego wydarzenia.
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            <a href="{{ route('rides.create', ['event_id' => $event->id]) }}" class="btn btn-success">
                                                <i class="bi bi-plus-circle"></i> Dodaj nowy przejazd
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Skrypt mapy Leaflet -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicjalizacja mapy
            const map = L.map('map').setView([{{ $event->latitude }}, {{ $event->longitude }}], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Dodanie markera
            L.marker([{{ $event->latitude }}, {{ $event->longitude }}])
                .addTo(map)
                .bindPopup('{{ $event->location_name }}')
                .openPopup();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicjalizacja map dla wszystkich przejazdów
            @if($event->has_ride_sharing && $event->rides && $event->rides->count() > 0)
            @foreach($event->rides as $ride)
            // Inicjalizacja mapy dla przejazdu {{ $ride->id }}
            const meetingMap{{ $ride->id }} = L.map('meeting-map-{{ $ride->id }}').setView([{{ $ride->meeting_latitude }}, {{ $ride->meeting_longitude }}], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(meetingMap{{ $ride->id }});

            // Dodanie markera
            L.marker([{{ $ride->meeting_latitude }}, {{ $ride->meeting_longitude }}])
                .addTo(meetingMap{{ $ride->id }})
                .bindPopup('{{ $ride->meeting_location_name }}')
                .openPopup();
            @endforeach
            @endif
        });
    </script>
</x-app-layout>
