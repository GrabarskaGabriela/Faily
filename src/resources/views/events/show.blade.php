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
                        <!-- Reszta zawartości -->
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
</x-app-layout>
