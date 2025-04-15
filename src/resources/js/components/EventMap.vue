<template>
    <div class="event-map-container mb-4">
        <div class="input-group mb-3">
            <input type="text" class="form-control" v-model="searchQuery" placeholder="Wyszukaj adres (np. Warszawa, ul. Marszałkowska 1)" @keyup.enter="searchLocation">
            <button class="btn btn-outline-secondary" type="button" @click="searchLocation">Wyszukaj</button>
        </div>

        <!-- Lista sugestii adresów -->
        <div class="address-suggestions" v-show="suggestions.length > 0">
            <div
                v-for="(suggestion, index) in suggestions"
                :key="index"
                class="address-suggestion"
                @click="selectSuggestion(suggestion)">
                {{ suggestion.display_name }}
            </div>
        </div>

        <!-- Dodajemy ref do elementu mapy -->
        <div ref="mapContainer" style="height: 400px; width: 100%; border: 1px solid #ccc; margin-bottom: 15px;"></div>

        <div class="coordinate-display" v-if="latitude && longitude">
            Wybrana lokalizacja: <strong>{{ latitude.toFixed(6) }}, {{ longitude.toFixed(6) }}</strong>
        </div>

        <!-- Dodajemy komunikat debugujący -->
        <div v-if="mapError" class="alert alert-danger">
            {{ mapError }}
        </div>
    </div>
</template>

<script>
export default {
    name: 'EventMap',
    props: {
        initialLatitude: {
            type: Number,
            default: 52.2297
        },
        initialLongitude: {
            type: Number,
            default: 21.0122
        },
        initialLocationName: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            latitude: this.initialLatitude,
            longitude: this.initialLongitude,
            locationName: this.initialLocationName,
            searchQuery: '',
            map: null,
            marker: null,
            mapError: '',
            leaflet: null,
            suggestions: []
        };
    },
    mounted() {
        console.log('EventMap component mounted');
        // Opóźnienie inicjalizacji, aby zapewnić załadowanie wszystkich zasobów
        this.initMapWithRetry();

        // Ustawienie searchQuery na podstawie initialLocationName
        if (this.initialLocationName) {
            this.searchQuery = this.initialLocationName;
        }
    },
    methods: {
        initMapWithRetry(attempt = 1) {
            console.log(`Próba inicjalizacji mapy #${attempt}`);

            // Ładowanie Leaflet dynamicznie jeśli nie jest dostępny
            if (typeof L === 'undefined') {
                console.log('Leaflet nie jest dostępny, ładuję dynamicznie...');
                this.mapError = 'Ładowanie biblioteki map...';

                // Ładujemy CSS Leaflet
                const leafletCSS = document.createElement('link');
                leafletCSS.rel = 'stylesheet';
                leafletCSS.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(leafletCSS);

                // Ładujemy JS Leaflet
                const leafletScript = document.createElement('script');
                leafletScript.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                leafletScript.onload = () => {
                    console.log('Leaflet załadowany dynamicznie');
                    this.leaflet = window.L;
                    this.initMap();
                };
                leafletScript.onerror = () => {
                    this.mapError = 'Nie udało się załadować biblioteki map. Odśwież stronę.';
                };
                document.head.appendChild(leafletScript);
                return;
            } else {
                this.leaflet = L;
                this.initMap();
            }
        },

        initMap() {
            try {
                console.log('Inicjalizacja mapy...');
                console.log('Kontener mapy ref:', this.$refs.mapContainer);

                // Sprawdzamy czy kontener mapy jest dostępny
                if (!this.$refs.mapContainer) {
                    this.mapError = 'Kontener mapy nie jest dostępny. Odśwież stronę.';
                    console.error('Kontener mapy nie jest dostępny');
                    return;
                }

                // Ustawienie jednoznacznego ID dla kontenera mapy
                const mapId = 'leaflet-map-' + Date.now();
                this.$refs.mapContainer.id = mapId;

                // Sprawdzenie wymiarów kontenera
                const containerWidth = this.$refs.mapContainer.offsetWidth;
                const containerHeight = this.$refs.mapContainer.offsetHeight;
                console.log(`Wymiary kontenera mapy: ${containerWidth}x${containerHeight}`);

                if (containerWidth === 0 || containerHeight === 0) {
                    console.warn('Kontener mapy ma zerowe wymiary, próbuję naprawić...');
                    this.$refs.mapContainer.style.height = '400px';
                    this.$refs.mapContainer.style.width = '100%';
                }

                // Inicjalizacja mapy
                this.map = this.leaflet.map(mapId).setView([this.latitude, this.longitude], 13);

                // Dodanie warstwy kafelków
                this.leaflet.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(this.map);

                // Dodanie markera
                this.marker = this.leaflet.marker([this.latitude, this.longitude], {
                    draggable: true
                }).addTo(this.map);

                // Obsługa zdarzeń
                this.marker.on('dragend', (event) => {
                    const position = this.marker.getLatLng();
                    this.latitude = position.lat;
                    this.longitude = position.lng;
                    this.reverseGeocode(position.lat, position.lng);
                });

                this.map.on('click', (e) => {
                    const position = e.latlng;
                    this.latitude = position.lat;
                    this.longitude = position.lng;
                    this.marker.setLatLng(position);
                    this.reverseGeocode(position.lat, position.lng);
                });

                // Po krótkim czasie wymuszamy przerysowanie mapy
                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                        console.log('Mapa przerysowana');
                    }
                }, 100);

                console.log('Mapa zainicjalizowana pomyślnie');
                this.mapError = '';

                // Emitujemy początkowe współrzędne - ważne dla wypełniania ukrytych pól
                this.emitLocationUpdate();
            } catch (error) {
                console.error('Błąd inicjalizacji mapy:', error);
                this.mapError = `Błąd inicjalizacji mapy: ${error.message}`;

                // Jeśli próba się nie powiodła, spróbujmy ponownie
                if (attempt < 3) {
                    setTimeout(() => {
                        this.initMapWithRetry(attempt + 1);
                    }, 1000);
                }
            }
        },

        // Funkcja do pobierania sugestii adresów
        getSuggestions() {
            if (this.searchQuery.length < 3) {
                this.suggestions = [];
                return;
            }

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&limit=5`)
                .then(response => response.json())
                .then(data => {
                    console.log('Sugestie adresów:', data);
                    this.suggestions = data;
                })
                .catch(error => {
                    console.error('Błąd pobierania sugestii:', error);
                    this.suggestions = [];
                });
        },

        // Obsługa wyboru sugestii
        selectSuggestion(suggestion) {
            const lat = parseFloat(suggestion.lat);
            const lon = parseFloat(suggestion.lon);

            this.latitude = lat;
            this.longitude = lon;
            this.searchQuery = suggestion.display_name;
            this.suggestions = []; // Ukryj sugestie

            this.map.setView([lat, lon], 16);
            this.marker.setLatLng([lat, lon]);

            if (suggestion.display_name) {
                this.locationName = suggestion.display_name.split(',').slice(0, 3).join(', ');
                this.emitLocationUpdate();
            }
        },

        searchLocation() {
            if (!this.searchQuery) return;
            console.log('Wyszukiwanie lokalizacji:', this.searchQuery);

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Wyniki wyszukiwania:', data);
                    if (data && data.length > 0) {
                        const location = data[0];
                        const lat = parseFloat(location.lat);
                        const lon = parseFloat(location.lon);

                        this.latitude = lat;
                        this.longitude = lon;

                        this.map.setView([lat, lon], 16);
                        this.marker.setLatLng([lat, lon]);

                        if (location.display_name) {
                            this.locationName = location.display_name.split(',').slice(0, 3).join(', ');
                            this.emitLocationUpdate();
                        }
                    } else {
                        alert('Nie znaleziono lokalizacji. Spróbuj ponownie.');
                    }
                })
                .catch(error => {
                    console.error('Błąd wyszukiwania:', error);
                    alert('Wystąpił błąd podczas wyszukiwania. Spróbuj ponownie.');
                });
        },

        reverseGeocode(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Wyniki reverse geocoding:', data);
                    if (data && data.display_name) {
                        this.locationName = data.display_name.split(',').slice(0, 3).join(', ');
                        this.searchQuery = this.locationName; // Aktualizacja pola wyszukiwania
                        this.emitLocationUpdate();
                    }
                })
                .catch(error => {
                    console.error('Błąd reverse geocoding:', error);
                });
        },

        emitLocationUpdate() {
            console.log('Emitowanie aktualizacji lokalizacji:', {
                latitude: this.latitude,
                longitude: this.longitude,
                locationName: this.locationName
            });

            this.$emit('location-updated', {
                latitude: this.latitude,
                longitude: this.longitude,
                locationName: this.locationName
            });
        }
    },
    // Dodajemy watch dla searchQuery, aby pobierać sugestie podczas wpisywania
    watch: {
        searchQuery: {
            handler: function(newVal) {
                if (newVal.length >= 3) {
                    this.getSuggestions();
                } else {
                    this.suggestions = [];
                }
            },
            debounce: 300 // Opóźnienie 300ms dla lepszej wydajności
        }
    }
};
</script>

<style scoped>
.event-map-container {
    position: relative;
}
.coordinate-display {
    margin-top: 5px;
    font-size: 0.9rem;
    color: #6c757d;
}
.address-suggestions {
    position: absolute;
    top: calc(2.5rem + 2px); /* Wysokość input + margines */
    left: 0;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ccc;
    border-radius: 0 0 4px 4px;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.address-suggestion {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}
.address-suggestion:last-child {
    border-bottom: none;
}
.address-suggestion:hover {
    background-color: #f0f0f0;
}
</style>
