<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.addEvent') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body class="bg-main text-color">
@include('includes.navbar')
<div class="page-container" id="app">
    <main class="py-4">
        <div class="container">
            <div class="main-container">
                <h2 class="mb-4">{{ __('messages.addevent.addEvent') }}</h2>

                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" id="event-form">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">{{ __('messages.addevent.eventTitle') }}</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('messages.addevent.eventDesc') }}</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">{{ __('messages.addevent.date') }}</label>
                                <input type="datetime-local" class="form-control" id="date" name="date" required>
                            </div>

                            <div class="mb-3">
                                <label for="peopleCount" class="form-label">{{ __('messages.addevent.availablePersonNumber') }}</label>

                                <input type="number" class="form-control" id="peopleCount" name="people_count" min="1" required>
                            </div>

                            <div class="mb-3">

                                <input type="file" class="d-none" id="eventPhotos" name="photos[]" multiple onchange="updateFileList()">

                                <label for="eventPhotos" class="btn btn-gradient text-color mt-2">
                                    {{ __('messages.addevent.addPhotos') }}
                                </label>

                                <div id="fileList" class="mt-2 small text-color">{{ __('messages.addevent.fileNotChoosen') }}</div>
                            </div>

                            <script>
                                function updateFileList() {
                                    const input = document.getElementById('eventPhotos');
                                    const output = document.getElementById('fileList');
                                    const files = Array.from(input.files).map(file => file.name);
                                    output.textContent = files.length ? files.join(', ') : '{{ __('messages.addevent.fileNotChoosen') }}';
                                }
                            </script>


                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location_name" class="form-label">{{ __('messages.addevent.locationName') }}</label>
                                <input type="text" class="form-control" id="location_name" name="location_name" placeholder="{{ __('messages.addevent.placeName') }}" required>
                            </div>


                            <input type="hidden" id="latitude" name="latitude" value="52.069">
                            <input type="hidden" id="longitude" name="longitude" value="19.480">


                            <div class="mb-3">
                                <label for="search-address" class="form-label">{{ __('messages.addevent.address') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search-address" placeholder="{{ __('messages.addevent.addressExample') }}">
                                    <button class="btn btn-outline-secondary" type="button" id="search-button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>


                            <div class="mb-3">
                                <div id="map-container" class="map-container"></div>
                                <div class="coordinate-display">
                                    {{ __('messages.addevent.selectedLocation') }} <strong id="coordinates-text">52.069000, 19.480000</strong>
                                </div>
                            </div>


                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="has_ride_sharing" name="has_ride_sharing" value="1">
                                <label class="form-check-label" for="has_ride_sharing">{{ __('messages.addevent.enableCarSharing') }}</label>
                            </div>
                        </div>
                    </div>


                    <div id="ride-sharing-form" style="display: none;" class="mt-4 mb-4 p-3">
                        <h4 class="mb-3">{{ __('messages.addevent.rideDetails') }}</h4>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_description" class="form-label">{{ __('messages.addevent.carDesc') }}</label>
                                    <input type="text" class="form-control" id="vehicle_description" name="vehicle_description" placeholder="{{ __('messages.addevent.carDescExample') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="avalible_seats" class="form-label">{{ __('messages.addevent.availableSeats') }}</label>
                                    <input type="number" class="form-control" id="avalible_seats" name="avalible_seats" min="1" value="1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meeting_location_name" class="form-label">{{ __('messages.addevent.meetingPlace') }}</label>
                                    <input type="text" class="form-control" id="meeting_location_name" name="meeting_location_name" placeholder="{{ __('messages.addevent.meetingPlaceExample') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="search-meeting-address" class="form-label">{{ __('messages.addevent.searchMeetingPlace') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search-meeting-address" placeholder="{{ __('messages.addevent.enterAddress') }}">
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
                                        {{ __('messages.addevent.selectedMeetingLocation') }} <strong id="meeting-coordinates-text">52.069000, 19.480000</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="meeting_latitude" name="meeting_latitude" value="52.069">
                        <input type="hidden" id="meeting_longitude" name="meeting_longitude" value="19.480">
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-lg px-4 btn-gradient text-color" id="submit-button">
                            <i class="bi bi-check-circle me-2" ></i>{{ __('messages.addevent.addEvent') }}
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
            fileCount.textContent = `{{ __('messages.addevent.fileSelection') }} ${input.files.length}`;
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
        console.log('{{ __('messages.addevent.mapLoadedInitialization') }}');

        setTimeout(() => {
            initMainMap();
            initRideSharingForm();
        }, 300);
    });

    function initMainMap() {
        console.log('{{ __('messages.addevent.leafletMapInitialization') }}');

        const mapContainer = document.getElementById('map-container');

        if (!mapContainer) {
            console.error('{{ __('messages.addevent.mapContainerNotFound') }}');
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
                    .catch(error => console.error('{{ __('messages.addevent.geocodingError') }}', error));
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
                    .catch(error => console.error('{{ __('messages.addevent.suggestionsError') }}', error));
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
                            alert('{{ __('messages.addevent.locationNotFoundRetry') }}');
                        }
                    })
                    .catch(error => {
                        console.error('{{ __('messages.addevent.searchError') }}', error);
                        alert('{{ __('messages.addevent.searchErrorRetry') }}');
                    });
            }

            searchButton.addEventListener('click', searchLocation);
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchLocation();
                    e.preventDefault();
                }
            });

            setTimeout(() => {
                map.invalidateSize();
                console.log('{{ __('messages.addevent.mapRedraw') }}');
            }, 500);
        } catch (error) {
            console.error('{{ __('messages.addevent.mapInitializationError') }}', error);
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
        console.log('{{ __('messages.addevent.meetingMapInitialization') }}');

        const mapContainer = document.getElementById('meeting-map-container');
        if (!mapContainer) {
            console.error('{{ __('messages.addevent.meetingMapContainerNotFound') }}');
            return;
        }

        if (mapContainer._leaflet_id) {
            console.log('{{ __('messages.addevent.meetingMapExists') }}');
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
                    .catch(error => console.error('{{ __('messages.addevent.meetingGeocodingError') }}', error));
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
                            alert('{{ __('messages.addevent.meetingLocationNotFoundRetry') }}');
                        }
                    })
                    .catch(error => {
                        console.error('{{ __('messages.addevent.meetingSearchError') }}', error);
                        alert('{{ __('messages.addevent.meetingSearchErrorRetry') }}');
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
                console.log('{{ __('messages.addevent.meetingMapRedraw') }}');
            }, 500);
        } catch (error) {
            console.error('{{ __('messages.addevent.meetingMapInitializationError') }}', error);
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
