<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - dodaj wydarzenie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Style dla sugestii adresów */
        .address-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background:  linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 .375rem .375rem;
            z-index: 1000;
            display: none;
        }
        .address-suggestion {
            padding: .5rem 1rem;
            cursor: pointer;
            transition: background-color .15s;
        }
        .address-suggestion:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        #map-container, #meeting-map-container {
            width: 100%;
            height: 400px !important;
            display: block !important;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .coordinate-display {
            margin-top: .5rem;
            font-size: .875rem;
            color: #6c757d;
        }

        .main-container {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: .5rem;
            padding: 2rem;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }

        #ride-sharing-form {
            background-color: rgba(255,255,255,.05);
            border-radius: .375rem;
        }
    </style>
</head>
<body class="bg-main text-white">
<div class="page-container" id="app">
    @include('includes.navbar')
    <main class="py-4">
        <div class="container">
            <div class="main-container">
                <h2 class="mb-4">Dodaj wydarzenie</h2>

                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" id="event-form">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Tytuł wydarzenia</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Opis wydarzenia</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Data wydarzenia</label>
                                <input type="datetime-local" class="form-control" id="date" name="date" required>
                            </div>

                            <div class="mb-3">
                                <label for="peopleCount" class="form-label">Ilość osób</label>

                                <input type="number" class="form-control" id="peopleCount" name="people_count" min="1" required>
                            </div>

                            <div class="mb-3">
                                <label for="eventPhotos" class="form-label">Dodaj zdjęcia</label>
                                <input type="file" class="form-control" id="eventPhotos" name="photos[]" multiple onchange="updateFileList()">
                                <div id="fileList" class="mt-2 small text-muted"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location_name" class="form-label">Nazwa lokalizacji</label>
                                <input type="text" class="form-control" id="location_name" name="location_name" placeholder="Nazwa miejsca" required>
                            </div>


                            <input type="hidden" id="latitude" name="latitude" value="52.069">
                            <input type="hidden" id="longitude" name="longitude" value="19.480">


                            <div class="mb-3">
                                <label for="search-address" class="form-label">Wyszukaj adres</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search-address" placeholder="np. Warszawa, ul. Marszałkowska 1">
                                    <button class="btn btn-outline-secondary" type="button" id="search-button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>


                            <div class="mb-3">
                                <div id="map-container" class="map-container"></div>
                                <div class="coordinate-display">
                                    Wybrana lokalizacja: <strong id="coordinates-text">52.069000, 19.480000</strong>
                                </div>
                            </div>


                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="has_ride_sharing" name="has_ride_sharing" value="1">
                                <label class="form-check-label" for="has_ride_sharing">Włącz współdzielenie przejazdów</label>
                            </div>
                        </div>
                    </div>


                    <div id="ride-sharing-form" style="display: none;" class="mt-4 mb-4 p-3">
                        <h4 class="mb-3">Szczegóły przejazdu</h4>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_description" class="form-label">Opis pojazdu</label>
                                    <input type="text" class="form-control" id="vehicle_description" name="vehicle_description" placeholder="np. Czerwony Ford Focus">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="avalible_seats" class="form-label">Dostępna liczba miejsc</label>
                                    <input type="number" class="form-control" id="avalible_seats" name="avalible_seats" min="1" value="1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meeting_location_name" class="form-label">Nazwa miejsca spotkania</label>
                                    <input type="text" class="form-control" id="meeting_location_name" name="meeting_location_name" placeholder="np. Parking przy galerii">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="search-meeting-address" class="form-label">Wyszukaj adres miejsca spotkania</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search-meeting-address" placeholder="Wpisz adres">
                                        <button class="btn btn-outline-secondary" type="button" id="search-meeting-button">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <div id="meeting-map-container" class="map-container" style="height: 200px;"></div>
                                    <div class="coordinate-display">
                                        Wybrana lokalizacja spotkania: <strong id="meeting-coordinates-text">52.069000, 19.480000</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="meeting_latitude" name="meeting_latitude" value="52.069">
                        <input type="hidden" id="meeting_longitude" name="meeting_longitude" value="19.480">
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-lg px-4 text-white"  style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);"id="submit-button">
                            <i class="bi bi-check-circle me-2" ></i>Dodaj wydarzenie
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    @include('includes.footer')
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script>
    function updateFileList() {
        const input = document.getElementById('eventPhotos');
        const list = document.getElementById('fileList');
        list.innerHTML = '';

        if (input.files.length > 0) {
            const fileCount = document.createElement('div');
            fileCount.textContent = `Wybrano plików: ${input.files.length}`;
            list.appendChild(fileCount);

            const fileNames = document.createElement('ul');
            fileNames.className = 'mt-1 ps-3';

            for (let i = 0; i < input.files.length; i++) {
                const item = document.createElement('li');
                item.textContent = input.files[i].name;
                fileNames.appendChild(item);
            }
            list.appendChild(fileNames);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM w pełni załadowany, inicjalizacja map...');

        setTimeout(() => {
            initMainMap();
            initRideSharingForm();
        }, 300);
    });

    function initMainMap() {
        console.log('Inicjalizacja głównej mapy Leaflet...');

        const mapContainer = document.getElementById('map-container');

        if (!mapContainer) {
            console.error('Nie znaleziono kontenera mapy!');
            return;
        }

        mapContainer.style.height = '300px';

        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const locationNameInput = document.getElementById('location_name');
        const searchInput = document.getElementById('search-address');
        const searchButton = document.getElementById('search-button');
        const coordinatesText = document.getElementById('coordinates-text');

        const initialLat = parseFloat(latitudeInput.value) || 52.069;
        const initialLng = parseFloat(longitudeInput.value) || 19.480;

        try {
            if (mapContainer._leaflet_id) {
                mapContainer._leaflet = null;
            }

            const map = L.map(mapContainer).setView([initialLat, initialLng], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);


            const marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(map);

            function updateCoordinates(lat, lng) {
                latitudeInput.value = lat;
                longitudeInput.value = lng;
                coordinatesText.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

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

            marker.on('dragend', function() {
                const position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });

            map.on('click', function(e)
            {
                marker.setLatLng(e.latlng);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
            });

            let suggestionElements = null;

            function showSuggestions(items)
            {
                if (suggestionElements) {
                    suggestionElements.remove();
                }

                suggestionElements = document.createElement('div');
                suggestionElements.className = 'address-suggestions';
                suggestionElements.style.display = 'block';


                items.forEach((item) => {
                    const suggestion = document.createElement('div');
                    suggestion.className = 'address-suggestion';
                    suggestion.textContent = item.display_name;
                    suggestion.addEventListener('click', () => selectSuggestion(item));
                    suggestionElements.appendChild(suggestion);
                });

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

                if (suggestionElements) {
                    suggestionElements.style.display = 'none';
                }
            }


            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length < 3) {
                    if (suggestionElements) {
                        suggestionElements.style.display = 'none';
                    }
                    return;
                }

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            showSuggestions(data);
                        } else if (suggestionElements) {
                            suggestionElements.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Błąd pobierania sugestii:', error));
            });


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

            // Nasłuchiwanie zdarzeń
            searchButton.addEventListener('click', searchLocation);
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchLocation();
                    e.preventDefault();
                }
            });

            setTimeout(() => {
                map.invalidateSize();
                console.log('Mapa przerysowana');
            }, 500);
        } catch (error) {
            console.error('Błąd podczas inicjalizacji mapy:', error);
        }
    }

    function initRideSharingForm() {
        const rideShareCheckbox = document.getElementById('has_ride_sharing');
        const rideShareForm = document.getElementById('ride-sharing-form');

        function toggleRideShareForm() {
            if (rideShareCheckbox && rideShareForm) {
                if (rideShareCheckbox.checked) {
                    rideShareForm.style.display = 'block';
                    setTimeout(() => initMeetingMap(), 300);
                } else {
                    rideShareForm.style.display = 'none';
                }
            }
        }

        if (rideShareCheckbox) {
            rideShareCheckbox.addEventListener('change', toggleRideShareForm);
            toggleRideShareForm();
        }
    }

    function initMeetingMap() {
        console.log('Inicjalizacja mapy miejsca spotkania...');

        const mapContainer = document.getElementById('meeting-map-container');
        if (!mapContainer) {
            console.error('Nie znaleziono kontenera mapy miejsca spotkania!');
            return;
        }

        if (mapContainer._leaflet_id) {
            console.log('Mapa miejsca spotkania już istnieje, pomijam inicjalizację.');
            return;
        }

        mapContainer.style.height = '200px';

        const latitudeInput = document.getElementById('meeting_latitude');
        const longitudeInput = document.getElementById('meeting_longitude');
        const locationNameInput = document.getElementById('meeting_location_name');
        const searchInput = document.getElementById('search-meeting-address');
        const searchButton = document.getElementById('search-meeting-button');
        const coordinatesText = document.getElementById('meeting-coordinates-text');


        const initialLat = parseFloat(latitudeInput.value) || 52.069;
        const initialLng = parseFloat(longitudeInput.value) || 19.480;

        try {

            const meetingMap = L.map(mapContainer).setView([initialLat, initialLng], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(meetingMap);

            const marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(meetingMap);


            function updateMeetingCoordinates(lat, lng) {
                latitudeInput.value = lat;
                longitudeInput.value = lng;
                coordinatesText.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

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

            marker.on('dragend', function() {
                const position = marker.getLatLng();
                updateMeetingCoordinates(position.lat, position.lng);
            });

            meetingMap.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateMeetingCoordinates(e.latlng.lat, e.latlng.lng);
            });

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

            setTimeout(() => {
                meetingMap.invalidateSize();
                console.log('Mapa miejsca spotkania przerysowana');
            }, 500);
        } catch (error) {
            console.error('Błąd podczas inicjalizacji mapy miejsca spotkania:', error);
        }
    }

    document.addEventListener('click', function(e) {
        const suggestions = document.querySelector('.address-suggestions');
        const searchInput = document.getElementById('search-address');

        if (suggestions && e.target !== searchInput && !suggestions.contains(e.target)) {
            suggestions.style.display = 'none';
        }
    });
</script>
</body>
</html>
