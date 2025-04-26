<template>
    <div class="map-container">
        <!-- Address search input -->
        <div class="input-group mb-3">
            <input type="text" class="form-control" v-model="searchQuery" placeholder="Wyszukaj adres (np. Warszawa, ul. Marszałkowska 1)" @keyup.enter="searchLocation">
            <button class="btn btn-outline-secondary" type="button" @click="searchLocation">Wyszukaj</button>
        </div>

        <!-- Address suggestions list -->
        <div class="address-suggestions" v-show="suggestions.length > 0">
            <div
                v-for="(suggestion, index) in suggestions"
                :key="index"
                class="address-suggestion"
                @click="selectSuggestion(suggestion)">
                {{ suggestion.display_name }}
            </div>
        </div>

        <!-- Map container -->
        <div :id="mapId" ref="mapContainer" class="map-element"></div>

        <!-- Coordinate display -->
        <div class="coordinate-display" v-if="latitude && longitude">
            Wybrana lokalizacja: <strong>{{ latitude.toFixed(6) }}, {{ longitude.toFixed(6) }}</strong>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import L from 'leaflet';

// Naprawianie problemu z ikonami
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow
});

export default {
    props: {
        center: {
            type: Array,
            default: () => [51.2101, 16.1619] // Legnica
        },
        zoom: {
            type: Number,
            default: 7
        },
        markers: {
            type: Array,
            default: () => []
        }
    },
    setup(props, { emit }) {
        const mapId = ref('map-' + Math.random().toString(36).substring(2, 9));
        const searchQuery = ref('');
        const suggestions = ref([]);
        const latitude = ref(props.center[0]);
        const longitude = ref(props.center[1]);

        let map = null;
        let mainMarker = null;
        let resizeObserver = null;

        // Search functionality methods
        const getSuggestions = () => {
            if (searchQuery.value.length < 3) {
                suggestions.value = [];
                return;
            }

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery.value)}&limit=5`)
                .then(response => response.json())
                .then(data => {
                    console.log('Sugestie adresów:', data);
                    suggestions.value = data;
                })
                .catch(error => {
                    console.error('Błąd pobierania sugestii:', error);
                    suggestions.value = [];
                });
        };

        const searchLocation = () => {
            if (!searchQuery.value) return;
            console.log('Wyszukiwanie lokalizacji:', searchQuery.value);

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery.value)}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Wyniki wyszukiwania:', data);
                    if (data && data.length > 0) {
                        const location = data[0];
                        const lat = parseFloat(location.lat);
                        const lon = parseFloat(location.lon);

                        latitude.value = lat;
                        longitude.value = lon;

                        map.setView([lat, lon], 16);

                        // Update marker position
                        if (mainMarker) {
                            mainMarker.setLatLng([lat, lon]);
                        }

                        // Emit location update
                        emit('location-updated', {
                            latitude: lat,
                            longitude: lon,
                            locationName: location.display_name
                        });
                    } else {
                        alert('Nie znaleziono lokalizacji. Spróbuj ponownie.');
                    }
                })
                .catch(error => {
                    console.error('Błąd wyszukiwania:', error);
                    alert('Wystąpił błąd podczas wyszukiwania. Spróbuj ponownie.');
                });
        };

        const selectSuggestion = (suggestion) => {
            const lat = parseFloat(suggestion.lat);
            const lon = parseFloat(suggestion.lon);

            latitude.value = lat;
            longitude.value = lon;
            searchQuery.value = suggestion.display_name;
            suggestions.value = []; // Hide suggestions

            map.setView([lat, lon], 16);

            // Update marker position
            if (mainMarker) {
                mainMarker.setLatLng([lat, lon]);
            }

            // Emit location update
            emit('location-updated', {
                latitude: lat,
                longitude: lon,
                locationName: suggestion.display_name
            });
        };

        const reverseGeocode = (lat, lng) => {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Wyniki reverse geocoding:', data);
                    if (data && data.display_name) {
                        searchQuery.value = data.display_name;

                        // Emit location update
                        emit('location-updated', {
                            latitude: lat,
                            longitude: lng,
                            locationName: data.display_name
                        });
                    }
                })
                .catch(error => {
                    console.error('Błąd reverse geocoding:', error);
                });
        };

        // Map initialization
        const initMap = () => {
            // Inicjalizacja mapy
            map = L.map(mapId.value, {
                zoomControl: true,
                scrollWheelZoom: true
            }).setView(props.center, props.zoom);

            // Dodanie warstwy OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Dodanie domyślnego markera na środku mapy
            mainMarker = L.marker(props.center, {
                draggable: true
            }).addTo(map);

            mainMarker.bindPopup('Tu jesteśmy :)').openPopup();

            // Obsługa przeciągania markera
            mainMarker.on('dragend', (event) => {
                const position = mainMarker.getLatLng();
                latitude.value = position.lat;
                longitude.value = position.lng;
                reverseGeocode(position.lat, position.lng);
            });

            // Obsługa kliknięcia na mapę
            map.on('click', (e) => {
                const position = e.latlng;
                latitude.value = position.lat;
                longitude.value = position.lng;
                mainMarker.setLatLng(position);
                reverseGeocode(position.lat, position.lng);
            });

            // Dodanie dodatkowych markerów
            if (props.markers.length > 0) {
                props.markers.forEach(markerData => {
                    const marker = L.marker([markerData.lat, markerData.lng]).addTo(map);

                    if (markerData.popup) {
                        marker.bindPopup(markerData.popup);
                    }
                });
            }

            // Nasłuchiwanie na zdarzenie resize okna
            window.addEventListener('resize', handleResize);

            // Użyj ResizeObserver dla dokładniejszego śledzenia zmian rozmiaru
            if (typeof ResizeObserver !== 'undefined') {
                resizeObserver = new ResizeObserver(() => {
                    if (map) map.invalidateSize();
                });

                const mapContainer = document.getElementById(mapId.value);
                if (mapContainer) {
                    resizeObserver.observe(mapContainer);
                }
            }

            // Przeładuj mapę po renderowaniu komponentu
            setTimeout(() => {
                if (map) map.invalidateSize();
            }, 100);
        };

        const handleResize = () => {
            if (map) {
                map.invalidateSize();
            }
        };

        onMounted(() => {
            initMap();

            // Dodatkowe opóźnione odświeżenie po pełnym renderowaniu strony
            setTimeout(() => {
                if (map) map.invalidateSize();
            }, 500);

            // Dodatkowe odświeżenie po dłuższym czasie, aby upewnić się że mapa jest prawidłowo wyświetlona
            setTimeout(() => {
                if (map) map.invalidateSize();
            }, 1500);
        });

        onUnmounted(() => {
            // Usuń nasłuchiwanie na zdarzenie resize
            window.removeEventListener('resize', handleResize);

            // Zatrzymaj obserwatora zmian rozmiaru
            if (resizeObserver) {
                resizeObserver.disconnect();
            }

            // Zniszcz mapę
            if (map) {
                map.remove();
                map = null;
            }
        });

        // Watch for searchQuery changes to get suggestions while typing
        watch(searchQuery, (newValue) => {
            if (newValue.length >= 3) {
                getSuggestions();
            } else {
                suggestions.value = [];
            }
        }, { debounce: 300 }); // 300ms delay for better performance

        return {
            mapId,
            searchQuery,
            suggestions,
            latitude,
            longitude,
            searchLocation,
            selectSuggestion,
            getMap: () => map
        };
    }
}
</script>

<style scoped>
.map-container {
    position: relative;
    width: 100%;
}

.map-element {
    width: 100%;
    height: 400px;
    border: 1px solid #ccc;
    margin-bottom: 15px;
}

.coordinate-display {
    margin-top: 5px;
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 15px;
}

.address-suggestions {
    position: absolute;
    top: calc(2.5rem + 2px); /* Input height + margin */
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

/* Style dla kontenera Leaflet */
:deep(.leaflet-container) {
    width: 100%;
    height: 100%;
    z-index: 1;
}
</style>
