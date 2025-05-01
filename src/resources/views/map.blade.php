<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.map') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        window.events = @json($events);
    </script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0 !important;
        }
        .map-container {
            flex: 1;
            width: 100%;
            height: 100%;
            position: relative;
            z-index: 1;
        }
        #map {
            width: 100%;
            height: 100%;
            position: absolute;
        }
        .map-controls, .map-info, .search-container {
            position: absolute;
            z-index: 1000;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 80%);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .map-controls { top: 10px; right: 10px; }
        .map-info { bottom: 30px; left: 10px; max-width: 300px; }
        .search-container { top: 10px; left: 60px; width: 300px; }
        .search-results {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 80%);
            border: 1px solid #000000;
            max-height: 200px;
            color: white;
            overflow-y: auto;
            display: none;
        }
        .search-result-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #ffffff;
        }
        .search-result-item:hover {
            background: linear-gradient(135deg, #1a1a2e 0%, #1e2d51 80%);
        }
        .leaflet-control-zoom a {
            background: linear-gradient(135deg, #1a1a2e 0%, #1e2d51 80%);
            color: white;
            border: black;
        }

        @media (max-width: 768px) {
            .search-container { width: calc(100% - 120px); left: 10px; }
            .map-info { bottom: 10px; max-width: calc(100% - 20px); }
        }
    </style>
    @include('includes.navbar')
</head>
<body>

<div class="page-container" id="app">

    <main>
        <div class="map-container">
            <div id="map"></div>

            <div class="search-container">
                <div class="input-group">
                    <input type="text" class="form-control" id="search-input" placeholder="{{ __('messages.map.searchPlace') }}">
                    <button class="btn btn-outline-light" type="button" id="search-button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div class="search-results" id="search-results"></div>
            </div>

            <div class="map-controls border-dark" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                <button class="btn text-white border-dark" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);" id="locate-me">
                    <i class="bi bi-geo-alt"></i> {{ __('messages.map.myLocation') }}
                </button>
            </div>

            <div class="map-info text-white" id="map-info"  style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                <h5>{{ __('messages.map.mapInfo') }}</h5>
                <p>{{ __('messages.map.clickMapInstruction') }}</p>
                <div id="coordinates"></div>
            </div>
        </div>
    </main>

    @include('includes.footer')
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([52.069, 19.480], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let currentMarker = null;

        function addMarker(lat, lng, popupContent = null) {
            if (currentMarker) {
                map.removeLayer(currentMarker);
            }
            currentMarker = L.marker([lat, lng], { draggable: true }).addTo(map);
            if (popupContent) currentMarker.bindPopup(popupContent).openPopup();

            currentMarker.on('dragend', () => {
                const pos = currentMarker.getLatLng();
                updateCoordinatesInfo(pos.lat, pos.lng);
                reverseGeocode(pos.lat, pos.lng);
            });
        }

        function updateCoordinatesInfo(lat, lng) {
            document.getElementById('coordinates').innerHTML = `
                <strong>{{ __('messages.map.latitude')}}</strong> ${lat.toFixed(6)}<br>
                <strong>{{__('messages.map.longitude')}}</strong> ${lng.toFixed(6)}
            `;
        }

        function reverseGeocode(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.display_name && currentMarker) {
                        currentMarker.bindPopup(`
                            <strong>${data.display_name}</strong><br>
                            <small>${lat.toFixed(6)}, ${lng.toFixed(6)}</small>
                        `).openPopup();
                    }
                });
        }

        function addEventMarkers(events, map) {
            events.forEach(event => {
                const lat = parseFloat(event.latitude);
                const lng = parseFloat(event.longitude);
                const marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup(`
                    <b>${event.title}</b><br>
                    ${event.location_name ?? ''}
                `);
            });
        }

        map.on('click', e => {
            addMarker(e.latlng.lat, e.latlng.lng);
            updateCoordinatesInfo(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        const locateButton = document.getElementById('locate-me');
        locateButton.addEventListener('click', () => {
            if ('geolocation' in navigator) {
                locateButton.disabled = true;
                locateButton.innerHTML = '{{ __('messages.map.locating') }}';
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        map.setView([lat, lng], 16);
                        addMarker(lat, lng, '{{ __('messages.map.yourLocation') }}');
                        updateCoordinatesInfo(lat, lng);
                        reverseGeocode(lat, lng);
                        locateButton.disabled = false;
                        locateButton.innerHTML = '<i class="bi bi-geo-alt"></i> {{ __('messages.map.myLocation') }}';
                    },
                    err => {
                        alert('{{ __('messages.map.alertGeolocalization') }}');
                        locateButton.disabled = false;
                        locateButton.innerHTML = '<i class="bi bi-geo-alt"></i> {{ __('messages.map.myLocation') }}';
                    }
                );
            } else {
                alert('{{ __('alertBrowserGeolocalization') }}');
            }
        });

        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const searchResults = document.getElementById('search-results');

        function searchLocation(query) {
            if (!query.trim()) return;
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`)
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if (data.length) {
                        data.forEach(result => {
                            const item = document.createElement('div');
                            item.className = 'search-result-item';
                            item.textContent = result.display_name;
                            item.addEventListener('click', () => {
                                const lat = parseFloat(result.lat);
                                const lng = parseFloat(result.lon);
                                map.setView([lat, lng], 16);
                                addMarker(lat, lng, result.display_name);
                                updateCoordinatesInfo(lat, lng);
                                searchResults.style.display = 'none';
                            });
                            searchResults.appendChild(item);
                        });
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.innerHTML = '<div class="search-result-item">{{ __('messages.map.noResults') }}</div>';
                        searchResults.style.display = 'block';
                    }
                });
        }

        searchButton.addEventListener('click', () => searchLocation(searchInput.value));
        searchInput.addEventListener('keypress', e => { if (e.key === 'Enter') searchLocation(searchInput.value); });
        searchInput.addEventListener('input', function () {
            if (this.value.length >= 3) {
                searchLocation(this.value);
            } else {
                searchResults.style.display = 'none';
            }
        });

        document.addEventListener('click', e => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });

        if (window.events && Array.isArray(window.events))
        {
            addEventMarkers(window.events, map);
        }
        setTimeout(() => map.invalidateSize(), 300);
    });
</script>
</body>
</html>
