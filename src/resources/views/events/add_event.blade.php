<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - dodaj wydarzenie</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        /* Style dla sugestii adresów */
        .address-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ccc;
            border-top: none;
            z-index: 1000;
            display: none;
        }
        .address-suggestion {
            padding: 8px 12px;
            cursor: pointer;
        }
        .address-suggestion:hover {
            background-color: #f0f0f0;
        }

        /* Style dla kontenera mapy */
        #map-container {
            height: 400px;
            width: 100%;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            z-index: 1;
        }

        .coordinate-display {
            margin-top: 5px;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body class="bg-main">
<div class="page-container" id="app">
    @include('includes.navbar')
    <main>
        <div class="container mt-5">
            <h2>Dodaj wydarzenie</h2>

            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" id="event-form">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Tytuł wydarzenia</label>
                    <input type="text" class="form-control" id="title" name="title" value="" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Opis wydarzenia</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Data wydarzenia</label>
                    <input type="datetime-local" class="form-control" id="date" name="date" value="" required>
                </div>

                <div class="mb-3">
                    <label for="location_name" class="form-label">Nazwa lokalizacji</label>
                    <input type="text" class="form-control" id="location_name" name="location_name" value="" placeholder="Nazwa miejsca" required>
                </div>

                <!-- Ukryte pola na współrzędne -->
                <input type="hidden" id="latitude" name="latitude" value="52.069">
                <input type="hidden" id="longitude" name="longitude" value="19.480">

                <!-- Bezpośrednia implementacja mapy zamiast komponentu Vue -->
                <div class="mb-4">
                    <!-- Wyszukiwarka adresów -->
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="search-address" placeholder="Wyszukaj adres (np. Warszawa, ul. Marszałkowska 1)">
                        <button class="btn btn-outline-secondary" type="button" id="search-button">Wyszukaj</button>
                    </div>

                    <!-- Kontener mapy -->
                    <div id="map-container" style="height: 400px !important; width: 100%; border: 1px solid #ccc; margin-bottom: 15px;"></div>

                    <!-- Wyświetlanie współrzędnych -->
                    <div class="coordinate-display">
                        Wybrana lokalizacja: <strong id="coordinates-text">52.069000, 19.480000</strong>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="eventPhotos" class="form-label">Dodaj zdjęcia</label>
                    <input type="file" class="form-control" id="eventPhotos" name="photos[]" multiple onchange="updateFileList()">
                    <ul id="fileList" class="mt-2"></ul>
                </div>

                <div class="mb-3">
                    <label for="peopleCount" class="form-label">Ilość osób</label>
                    <input type="number" class="form-control" id="peopleCount" name="people_count" min="1" value="" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="has_ride_sharing" name="has_ride_sharing" value="1">
                    <label class="form-check-label" for="has_ride_sharing">Włącz współdzielenie przejazdów</label>
                </div>

                <div id="ride-sharing-form" style="display: none;" class="mt-4 mb-4 p-3 border rounded bg-light">
                    <h4>Szczegóły przejazdu</h4>

                    <div class="mb-3">
                        <label for="vehicle_description" class="form-label">Opis pojazdu</label>
                        <input type="text" class="form-control" id="vehicle_description" name="vehicle_description" placeholder="np. Czerwony Ford Focus">
                    </div>

                    <div class="mb-3">
                        <label for="avalible_seats" class="form-label">Dostępna liczba miejsc</label>
                        <input type="number" class="form-control" id="avalible_seats" name="avalible_seats" min="1" value="1">
                    </div>

                    <div class="mb-3">
                        <label for="meeting_location_name" class="form-label">Nazwa miejsca spotkania</label>
                        <input type="text" class="form-control" id="meeting_location_name" name="meeting_location_name" placeholder="np. Parking przy galerii">
                    </div>

                    <div class="mb-3">
                        <h5>Lokalizacja miejsca spotkania</h5>

                        <!-- Pole wyszukiwania -->
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="search-meeting-address" placeholder="Wyszukaj adres miejsca spotkania">
                            <button class="btn btn-outline-secondary" type="button" id="search-meeting-button">Wyszukaj</button>
                        </div>

                        <!-- Kontener mapy dla miejsca spotkania -->
                        <div id="meeting-map-container" style="height: 300px !important; width: 100%; border: 1px solid #ccc; margin-bottom: 15px;"></div>

                        <!-- Wyświetlanie współrzędnych miejsca spotkania -->
                        <div class="coordinate-display">
                            Wybrana lokalizacja spotkania: <strong id="meeting-coordinates-text">52.069000, 19.480000</strong>
                        </div>
                    </div>

                    <!-- Ukryte pola na współrzędne miejsca spotkania -->
                    <input type="hidden" id="meeting_latitude" name="meeting_latitude" value="52.069">
                    <input type="hidden" id="meeting_longitude" name="meeting_longitude" value="19.480">
                </div>

                <button type="submit" class="btn btn-primary btn-lg" id="submit-button">Dodaj ogłoszenie do oferty</button>
            </form>
        </div>
    </main>
    @include('includes.footer')
</div>

<!-- Istniejący skrypt zmodyfikowany o obsługę formularza przejazdów -->
<script>
    // Funkcja dla listy plików
    function updateFileList() {
        const input = document.getElementById('eventPhotos');
        const list = document.getElementById('fileList');
        list.innerHTML = '';

        if (input.files.length > 0) {
            for (let i = 0; i < input.files.length; i++) {
                const item = document.createElement('li');
                item.textContent = input.files[i].name;
                list.appendChild(item);
            }
        }
    }

    // Inicjalizacja mapy
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Inicjalizacja mapy Leaflet...');

        const mapContainer = document.getElementById('map-container');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const locationNameInput = document.getElementById('location_name');
        const searchInput = document.getElementById('search-address');
        const searchButton = document.getElementById('search-button');
        const coordinatesText = document.getElementById('coordinates-text');

        // Początkowe współrzędne
        const initialLat = parseFloat(latitudeInput.value) || 52.069;
        const initialLng = parseFloat(longitudeInput.value) || 19.480;

        // Inicjalizacja mapy
        const map = L.map('map-container').setView([initialLat, initialLng], 6);

        // Dodanie warstwy kafelków
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Dodanie markera
        const marker = L.marker([initialLat, initialLng], {
            draggable: true
        }).addTo(map);

        // Funkcja aktualizująca współrzędne
        function updateCoordinates(lat, lng) {
            latitudeInput.value = lat;
            longitudeInput.value = lng;
            coordinatesText.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            // Reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        const locationName = data.display_name.split(',').slice(0, 3).join(', ');
                        locationNameInput.value = locationName;
                    }
                })
                .catch(error => console.error('Błąd geokodowania:', error));
        }

        // Obsługa zdarzeń
        marker.on('dragend', function() {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });

        // Funkcja dodająca podpowiedzi adresów
        let suggestions = [];
        let suggestionElements = null;

        function showSuggestions(items) {
            // Usuń poprzednie sugestie jeśli istnieją
            if (suggestionElements) {
                suggestionElements.remove();
            }

            // Utwórz kontener na sugestie
            suggestionElements = document.createElement('div');
            suggestionElements.className = 'address-suggestions';
            suggestionElements.style.display = 'block';

            // Dodaj każdą sugestię
            items.forEach((item, index) => {
                const suggestion = document.createElement('div');
                suggestion.className = 'address-suggestion';
                suggestion.textContent = item.display_name;
                suggestion.addEventListener('click', () => selectSuggestion(item));
                suggestionElements.appendChild(suggestion);
            });

            // Dodaj do DOM
            const searchControl = searchInput.parentNode;
            searchControl.style.position = 'relative';
            searchControl.appendChild(suggestionElements);
        }

        function selectSuggestion(item) {
            const lat = parseFloat(item.lat);
            const lng = parseFloat(item.lon);

            searchInput.value = item.display_name;

            map.setView([lat, lng], 16);
            marker.setLatLng([lat, lng]);
            updateCoordinates(lat, lng);

            // Ukryj sugestie
            if (suggestionElements) {
                suggestionElements.style.display = 'none';
            }
        }

        // Obsługa wpisywania w pole wyszukiwania
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            if (query.length < 3) {
                if (suggestionElements) {
                    suggestionElements.style.display = 'none';
                }
                return;
            }

            // Pobierz sugestie
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        suggestions = data;
                        showSuggestions(suggestions);
                    } else {
                        if (suggestionElements) {
                            suggestionElements.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Błąd pobierania sugestii:', error);
                });
        });

        // Obsługa wyszukiwania
        function searchLocation() {
            const query = searchInput.value;
            if (!query) return;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const location = data[0];
                        const lat = parseFloat(location.lat);
                        const lng = parseFloat(location.lon);

                        map.setView([lat, lng], 16);
                        marker.setLatLng([lat, lng]);
                        updateCoordinates(lat, lng);

                        // Ukryj sugestie
                        if (suggestionElements) {
                            suggestionElements.style.display = 'none';
                        }
                    } else {
                        alert('Nie znaleziono lokalizacji. Spróbuj ponownie.');
                    }
                })
                .catch(error => {
                    console.error('Błąd wyszukiwania:', error);
                    alert('Wystąpił błąd podczas wyszukiwania. Spróbuj ponownie.');
                });
        }

        searchButton.addEventListener('click', searchLocation);
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchLocation();
                e.preventDefault();
            }
        });

        // Po inicjalizacji wymuszamy przerysowanie mapy
        setTimeout(() => {
            if (map) {
                map.invalidateSize();
                console.log('Mapa przerysowana');
            }
        }, 100);

        console.log('Mapa zainicjalizowana bezpośrednio przez Leaflet');

        // KOD DODANY DO OBSŁUGI FORMULARZA PRZEJAZDÓW

        // Pobranie referencji do checkboxa
        const rideShareCheckbox = document.getElementById('has_ride_sharing');
        const rideShareForm = document.getElementById('ride-sharing-form');

        // Funkcja pokazująca/ukrywająca formularz przejazdów
        function toggleRideShareForm() {
            if(rideShareCheckbox && rideShareForm) {
                if(rideShareCheckbox.checked) {
                    rideShareForm.style.display = 'block';
                    initMeetingMap(); // Inicjalizacja mapy miejsca spotkania
                } else {
                    rideShareForm.style.display = 'none';
                }
            }
        }

        // Nasłuchiwanie zmiany stanu checkboxa
        if(rideShareCheckbox) {
            rideShareCheckbox.addEventListener('change', toggleRideShareForm);

            // Inicjalizacja stanu formularza przy ładowaniu strony
            toggleRideShareForm();
        }

        // Funkcja inicjalizująca mapę miejsca spotkania
        function initMeetingMap() {
            console.log('Inicjalizacja mapy miejsca spotkania...');

            const mapContainer = document.getElementById('meeting-map-container');
            if (!mapContainer) return;

            const latitudeInput = document.getElementById('meeting_latitude');
            const longitudeInput = document.getElementById('meeting_longitude');
            const locationNameInput = document.getElementById('meeting_location_name');
            const searchInput = document.getElementById('search-meeting-address');
            const searchButton = document.getElementById('search-meeting-button');
            const coordinatesText = document.getElementById('meeting-coordinates-text');

            // Sprawdzenie, czy mapa już istnieje
            if (mapContainer._leaflet_id) {
                console.log('Mapa miejsca spotkania już istnieje, odświeżam...');
                return;
            }

            // Początkowe współrzędne
            const initialLat = parseFloat(latitudeInput.value) || 52.069;
            const initialLng = parseFloat(longitudeInput.value) || 19.480;

            // Inicjalizacja mapy
            const meetingMap = L.map('meeting-map-container').setView([initialLat, initialLng], 6);

            // Dodanie warstwy kafelków
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(meetingMap);

            // Dodanie markera
            const marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(meetingMap);

            // Funkcja aktualizująca współrzędne
            function updateMeetingCoordinates(lat, lng) {
                latitudeInput.value = lat;
                longitudeInput.value = lng;
                coordinatesText.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

                // Reverse geocoding
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            const locationName = data.display_name.split(',').slice(0, 3).join(', ');
                            locationNameInput.value = locationName;
                        }
                    })
                    .catch(error => console.error('Błąd geokodowania miejsca spotkania:', error));
            }

            // Obsługa zdarzeń
            marker.on('dragend', function() {
                const position = marker.getLatLng();
                updateMeetingCoordinates(position.lat, position.lng);
            });

            meetingMap.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateMeetingCoordinates(e.latlng.lat, e.latlng.lng);
            });

            // Funkcja wyszukiwania lokalizacji
            function searchMeetingLocation() {
                const query = searchInput.value;
                if (!query) return;

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const location = data[0];
                            const lat = parseFloat(location.lat);
                            const lng = parseFloat(location.lon);

                            meetingMap.setView([lat, lng], 16);
                            marker.setLatLng([lat, lng]);
                            updateMeetingCoordinates(lat, lng);
                        } else {
                            alert('Nie znaleziono lokalizacji. Spróbuj ponownie.');
                        }
                    })
                    .catch(error => {
                        console.error('Błąd wyszukiwania miejsca spotkania:', error);
                        alert('Wystąpił błąd podczas wyszukiwania. Spróbuj ponownie.');
                    });
            }

            // Obsługa wyszukiwania
            if (searchButton) {
                searchButton.addEventListener('click', searchMeetingLocation);
            }

            if (searchInput) {
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        searchMeetingLocation();
                        e.preventDefault();
                    }
                });
            }

            // Po inicjalizacji wymuszamy przerysowanie mapy
            setTimeout(() => {
                if (meetingMap) {
                    meetingMap.invalidateSize();
                    console.log('Mapa miejsca spotkania przerysowana');
                }
            }, 100);

            console.log('Mapa miejsca spotkania zainicjalizowana');
        }
    });

    // Ukryj sugestie po kliknięciu poza polem wyszukiwania
    document.addEventListener('click', function(e) {
        const suggestions = document.querySelector('.address-suggestions');
        const searchInput = document.getElementById('search-address');

        if (suggestions && e.target !== searchInput && !suggestions.contains(e.target)) {
            suggestions.style.display = 'none';
        }
    });

    // Diagnostyka
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== DIAGNOSTYKA LEAFLET ===');
        console.log('Leaflet dostępny globalnie:', typeof L !== 'undefined');
        if (typeof L !== 'undefined') {
            console.log('Wersja Leaflet:', L.version);
        }

        setTimeout(() => {
            const mapContainer = document.getElementById('map-container');
            const leafletContainer = document.querySelector('.leaflet-container');

            console.log('Kontener mapy istnieje:', !!mapContainer);
            console.log('Leaflet kontener istnieje:', !!leafletContainer);

            if (mapContainer) {
                console.log('Wymiary kontenera mapy:',
                    'szerokość=' + mapContainer.offsetWidth,
                    'wysokość=' + mapContainer.offsetHeight);
            }

            if (leafletContainer) {
                console.log('Wymiary kontenera Leaflet:',
                    'szerokość=' + leafletContainer.offsetWidth,
                    'wysokość=' + leafletContainer.offsetHeight);

                const tiles = document.querySelectorAll('.leaflet-tile');
                console.log('Liczba kafelków mapy:', tiles.length);
            }
        }, 1000);
    });
</script>

</body>
</html>
