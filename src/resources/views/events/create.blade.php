<!-- resources/views/events/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Utwórz nowe wydarzenie</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #map { height: 400px; width: 100%; }

        /* Style dla podpowiedzi adresów */
        .address-search-container {
            position: relative;
            margin-bottom: 20px;
        }
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
    </style>
</head>
<body>
<h1>Utwórz nowe wydarzenie</h1>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('events.store') }}" id="event-form">
    @csrf

    <div>
        <label for="title">Tytuł:</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required>
    </div>

    <div>
        <label for="description">Opis:</label>
        <textarea id="description" name="description">{{ old('description') }}</textarea>
    </div>

    <div>
        <label for="date">Data:</label>
        <input type="date" id="date" name="date" value="{{ old('date') }}" required>
    </div>

    <div class="address-search-container">
        <label for="address">Adres:</label>
        <input type="text" id="address" placeholder="Wprowadź adres wydarzenia" value="{{ old('address') }}">
        <button type="button" id="search-button">Szukaj</button>
        <div id="address-suggestions" class="address-suggestions"></div>
    </div>

    <div class="search-tips" style="font-size: 0.9em; margin: 5px 0 15px; color: #666;">
        <p>Wskazówki dotyczące wyszukiwania:</p>
        <ul>
            <li>Wpisz pełny adres, np. "ul. Marszałkowska 1, Warszawa"</li>
            <li>Spróbuj dodać kod pocztowy lub nazwę miasta</li>
            <li>Możesz wyszukać także charakterystyczne miejsca</li>
            <li>Jeśli nie możesz znaleźć dokładnego adresu, oznacz lokalizację bezpośrednio na mapie</li>
        </ul>
    </div>

    <div>
        <label for="location_name">Nazwa lokalizacji:</label>
        <input type="text" id="location_name" name="location_name" value="{{ old('location_name') }}" required>
    </div>

    <!-- Ukryte pola do przechowywania współrzędnych -->
    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

    <div>
        <label for="has_ride_sharing">Dojazd grupowy:</label>
        <input type="checkbox" id="has_ride_sharing" name="has_ride_sharing" value="1" {{ old('has_ride_sharing') ? 'checked' : '' }}>
    </div>

    <div id="map"></div>
    <p>Możesz przesunąć znacznik, aby dokładnie wskazać lokalizację.</p>

    <button type="submit" id="submit-button" disabled>Zapisz wydarzenie</button>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicjalizacja mapy (domyślnie Polska)
        var map = L.map('map').setView([52.069, 19.480], 6);

        // Dodanie warstwy OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Inicjalizacja markera
        var marker = null;

        // Elementy DOM
        var addressInput = document.getElementById('address');
        var suggestionsContainer = document.getElementById('address-suggestions');
        var submitButton = document.getElementById('submit-button');

        // Funkcja aktualizująca lokalizację
        function updateLocation(latlng, locationName) {
            console.log("Aktualizacja lokalizacji:", latlng, locationName);

            // Aktualizacja ukrytych pól
            document.getElementById('latitude').value = latlng.lat;
            document.getElementById('longitude').value = latlng.lng;

            // Aktualizacja nazwy lokalizacji, jeśli podana
            if (locationName && document.getElementById('location_name').value === '') {
                document.getElementById('location_name').value = locationName;
            }

            // Centrowanie mapy
            map.setView(latlng, 16);

            // Dodanie lub aktualizacja markera
            if (marker === null) {
                marker = L.marker(latlng, {draggable: true}).addTo(map);

                // Aktualizacja pól przy przeciągnięciu markera
                marker.on('dragend', function() {
                    var position = marker.getLatLng();
                    document.getElementById('latitude').value = position.lat;
                    document.getElementById('longitude').value = position.lng;

                    // Reverse geocoding - pobranie adresu z współrzędnych
                    fetchReverseGeocode(position);
                });
            } else {
                marker.setLatLng(latlng);
            }

            // Odblokowanie przycisku zapisu
            submitButton.disabled = false;
        }

        // Funkcja wykonująca wyszukiwanie adresu
        function searchAddress(query) {
            console.log("Wyszukiwanie adresu:", query);
            if (!query || query.trim() === '') return;

            // Pokaż jakiś komunikat ładowania
            suggestionsContainer.innerHTML = '<div class="address-suggestion">Wyszukiwanie...</div>';
            suggestionsContainer.style.display = 'block';

            fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=5`)
                .then(response => {
                    console.log("Odpowiedź API:", response);
                    return response.json();
                })
                .then(data => {
                    console.log("Dane z wyszukiwania:", data);
                    suggestionsContainer.innerHTML = '';

                    if (data.features && data.features.length > 0) {
                        data.features.forEach(feature => {
                            var properties = feature.properties;
                            var label = properties.name || '';

                            // Konstruowanie czytelnej etykiety
                            if (properties.street) {
                                label = properties.street;
                                if (properties.housenumber) {
                                    label += ' ' + properties.housenumber;
                                }
                            }

                            if (properties.city) {
                                label += label ? ', ' + properties.city : properties.city;
                            }

                            if (!label && properties.state) {
                                label = properties.state;
                            }

                            var item = document.createElement('div');
                            item.className = 'address-suggestion';
                            item.textContent = label || "Brak nazwy lokalizacji";
                            item.addEventListener('click', function() {
                                addressInput.value = this.textContent;
                                suggestionsContainer.style.display = 'none';

                                var coords = feature.geometry.coordinates;
                                var latlng = L.latLng(coords[1], coords[0]);
                                updateLocation(latlng, properties.name);
                            });
                            suggestionsContainer.appendChild(item);
                        });
                    } else {
                        var item = document.createElement('div');
                        item.className = 'address-suggestion';
                        item.textContent = "Nie znaleziono adresu. Spróbuj inaczej lub zaznacz na mapie.";
                        suggestionsContainer.appendChild(item);
                    }
                })
                .catch(error => {
                    console.error("Błąd wyszukiwania:", error);
                    suggestionsContainer.innerHTML = '<div class="address-suggestion">Błąd wyszukiwania. Spróbuj ponownie.</div>';
                });
        }

        // Funkcja pobierająca adres z współrzędnych
        function fetchReverseGeocode(latlng) {
            console.log("Reverse geocoding dla:", latlng);

            fetch(`https://photon.komoot.io/reverse?lon=${latlng.lng}&lat=${latlng.lat}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Dane z reverse geocoding:", data);

                    if (data.features && data.features.length > 0) {
                        var feature = data.features[0];
                        var properties = feature.properties;
                        var label = '';

                        if (properties.street) {
                            label = properties.street;
                            if (properties.housenumber) {
                                label += ' ' + properties.housenumber;
                            }
                        } else if (properties.name) {
                            label = properties.name;
                        }

                        if (properties.city) {
                            label += label ? ', ' + properties.city : properties.city;
                        }

                        addressInput.value = label || "Brak adresu dla tej lokalizacji";
                    }
                })
                .catch(error => {
                    console.error("Błąd reverse geocoding:", error);
                });
        }

        // Nasłuchiwanie na wpisywanie w polu adresu
        var searchTimeout;
        addressInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            var query = this.value.trim();

            if (query.length < 3) {
                suggestionsContainer.style.display = 'none';
                return;
            }

            // Opóźnienie wyszukiwania o 300ms, aby uniknąć zbyt wielu zapytań podczas pisania
            searchTimeout = setTimeout(function() {
                searchAddress(query);
            }, 300);
        });

        // Przycisk wyszukiwania
        document.getElementById('search-button').addEventListener('click', function() {
            console.log("Kliknięto przycisk wyszukiwania");
            var query = addressInput.value.trim();
            if (query) {
                searchAddress(query);
            } else {
                alert("Proszę wprowadzić adres do wyszukania");
            }
        });

        // Ukryj sugestie po kliknięciu poza kontenerem
        document.addEventListener('click', function(e) {
            if (!suggestionsContainer.contains(e.target) && e.target !== addressInput) {
                suggestionsContainer.style.display = 'none';
            }
        });

        // Umożliwienie zaznaczenia lokalizacji przez kliknięcie na mapę
        map.on('click', function(e) {
            console.log("Kliknięto na mapę:", e.latlng);
            updateLocation(e.latlng);
            fetchReverseGeocode(e.latlng);
        });

        // Sprawdź, czy mamy już współrzędne (np. przy błędach walidacji)
        var savedLat = document.getElementById('latitude').value;
        var savedLng = document.getElementById('longitude').value;

        if (savedLat && savedLng) {
            var lat = parseFloat(savedLat);
            var lng = parseFloat(savedLng);

            // Sprawdź, czy współrzędne są w rozsądnym zakresie dla Polski
            if (lat >= 49 && lat <= 55 && lng >= 14 && lng <= 24) {
                console.log("Znaleziono zapisane współrzędne w zakresie Polski:", lat, lng);
                var position = L.latLng(lat, lng);
                updateLocation(position);
                fetchReverseGeocode(position);
            } else {
                console.log("Zapisane współrzędne poza zakresem Polski, ustawiam domyślną lokalizację");
                // Ustaw widok na centrum Polski
                map.setView([52.069, 19.480], 6);
            }
        }

        // Walidacja formularza przed wysłaniem
        document.getElementById('event-form').addEventListener('submit', function(e) {
            var latitude = document.getElementById('latitude').value;
            var longitude = document.getElementById('longitude').value;

            if (!latitude || !longitude) {
                e.preventDefault();
                alert('Proszę wprowadzić prawidłowy adres lub zaznaczyć lokalizację na mapie.');
            }
        });

        // Dodaj kontrolkę wyszukiwania bezpośrednio w mapie
        var searchControl = L.Control.extend({
            options: {
                position: 'topleft'
            },

            onAdd: function() {
                var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-search');
                var searchButton = L.DomUtil.create('a', '', container);
                searchButton.href = '#';
                searchButton.title = 'Szukaj na mapie';
                searchButton.innerHTML = '🔍';

                L.DomEvent.on(searchButton, 'click', function(e) {
                    L.DomEvent.stop(e);
                    var searchQuery = prompt('Wpisz adres do wyszukania na mapie:');
                    if (searchQuery) {
                        fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(searchQuery)}&limit=1`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.features && data.features.length > 0) {
                                    var feature = data.features[0];
                                    var coords = feature.geometry.coordinates;
                                    var latlng = L.latLng(coords[1], coords[0]);
                                    updateLocation(latlng, feature.properties.name);

                                    // Aktualizacja pola adresu
                                    var address = '';
                                    var props = feature.properties;

                                    if (props.street) {
                                        address = props.street;
                                        if (props.housenumber) {
                                            address += ' ' + props.housenumber;
                                        }
                                    } else if (props.name) {
                                        address = props.name;
                                    }

                                    if (props.city) {
                                        address += address ? ', ' + props.city : props.city;
                                    }

                                    addressInput.value = address;
                                } else {
                                    alert('Nie znaleziono adresu. Spróbuj inaczej lub zaznacz bezpośrednio na mapie.');
                                }
                            })
                            .catch(error => {
                                console.error("Błąd wyszukiwania na mapie:", error);
                                alert('Wystąpił błąd podczas wyszukiwania. Spróbuj ponownie.');
                            });
                    }
                });

                return container;
            }
        });

        map.addControl(new searchControl());

        console.log("Inicjalizacja mapy zakończona");
    });
</script>

</body>
</html>
