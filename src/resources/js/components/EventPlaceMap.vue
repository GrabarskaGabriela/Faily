<template>
    <div class="vue-map-wrapper">
        <div class="form-container">
            <div class="mb-3">
                <label for="location-name" class="form-label">Nazwa lokalizacji</label>
                <input
                    type="text"
                    class="form-control"
                    id="location-name"
                    v-model="locationName"
                    placeholder="Nazwa miejsca"
                    required
                >
            </div>

            <input type="hidden" id="latitude" name="latitude" :value="latitude">
            <input type="hidden" id="longitude" name="longitude" :value="longitude">

            <div class="mb-3">
                <label for="search-address" class="form-label">Wyszukaj adres</label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control"
                        id="search-address"
                        v-model="searchQuery"
                        @input="handleSearchInput"
                        placeholder="np. Warszawa, ul. Marszałkowska 1"
                    >
                    <button class="btn btn-outline-secondary" type="button" @click="searchLocation">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                <div class="address-suggestions" v-if="showSuggestions && suggestions.length">
                    <div
                        class="address-suggestion"
                        v-for="(suggestion, index) in suggestions"
                        :key="index"
                        @click="selectSuggestion(suggestion)"
                    >
                        {{ suggestion.display_name }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div ref="mapContainer" class="event-place-map"></div>
                <div class="coordinate-display">
                    Wybrana lokalizacja: <strong>{{ formatCoordinates }}</strong>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import 'leaflet/dist/leaflet.css';

export default {
    name: 'EventPlaceMap',
    props: {
        initialLatitude: {
            type: Number,
            default: 52.069
        },
        initialLongitude: {
            type: Number,
            default: 19.480
        },
        initialLocationName: {
            type: String,
            default: ''
        },
        zoomLevel: {
            type: Number,
            default: 6
        },
        mapContainerId: {
            type: String,
            default: 'event-place-map'
        }
    },
    data() {
        return {
            map: null,
            marker: null,
            latitude: this.initialLatitude,
            longitude: this.initialLongitude,
            locationName: this.initialLocationName,
            searchQuery: '',
            suggestions: [],
            showSuggestions: false
        };
    },
    computed: {
        formatCoordinates() {
            return `${this.latitude.toFixed(6)}, ${this.longitude.toFixed(6)}`;
        }
    },
    methods: {
        initMap() {
            const L = window.L || require('leaflet');

            if (this.$refs.mapContainer && !this.map) {
                this.map = L.map(this.$refs.mapContainer).setView(
                    [this.latitude, this.longitude],
                    this.zoomLevel
                );

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(this.map);

                this.marker = L.marker([this.latitude, this.longitude], {
                    draggable: true
                }).addTo(this.map);

                this.marker.on('dragend', this.handleMarkerDrag);

                this.map.on('click', this.handleMapClick);

                setTimeout(() => {
                    this.map.invalidateSize();
                }, 300);
            }
        },

        handleMarkerDrag(event) {
            const position = this.marker.getLatLng();
            this.updateCoordinates(position.lat, position.lng);
        },

        handleMapClick(event) {
            if (this.marker) {
                this.marker.setLatLng(event.latlng);
            }
            this.updateCoordinates(event.latlng.lat, event.latlng.lng);
        },

        updateCoordinates(lat, lng) {
            this.latitude = lat;
            this.longitude = lng;
            this.reverseGeocode(lat, lng);
            this.emitUpdate();
        },

        updateMarkerPosition() {
            if (this.marker && this.map) {
                this.marker.setLatLng([this.latitude, this.longitude]);
                this.map.setView([this.latitude, this.longitude], this.map.getZoom());
            }
        },

        async reverseGeocode(lat, lng) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                const data = await response.json();

                if (data && data.display_name) {
                    const locationName = data.display_name.split(',').slice(0, 3).join(', ');
                    this.locationName = locationName;
                }
            } catch (error) {
                console.error('Geocoding error:', error);
            }
        },

        async handleSearchInput() {
            if (this.searchQuery.trim().length < 3) {
                this.showSuggestions = false;
                return;
            }

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&limit=5`);
                const data = await response.json();

                if (data && data.length > 0) {
                    this.suggestions = data;
                    this.showSuggestions = true;
                } else {
                    this.showSuggestions = false;
                }
            } catch (error) {
                console.error('Suggestion download error:', error);
                this.showSuggestions = false;
            }
        },

        selectSuggestion(suggestion) {
            this.searchQuery = suggestion.display_name;
            this.locationName = suggestion.display_name;

            const lat = parseFloat(suggestion.lat);
            const lng = parseFloat(suggestion.lon);

            this.latitude = lat;
            this.longitude = lng;

            if (this.map && this.marker) {
                this.map.setView([lat, lng], 16);
                this.marker.setLatLng([lat, lng]);
            }

            this.showSuggestions = false;
            this.emitUpdate();
        },

        async searchLocation() {
            if (!this.searchQuery.trim()) return;

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();

                if (data && data.length > 0) {
                    const location = data[0];
                    const lat = parseFloat(location.lat);
                    const lng = parseFloat(location.lon);

                    this.latitude = lat;
                    this.longitude = lng;
                    this.locationName = location.display_name;

                    if (this.map && this.marker) {
                        this.map.setView([lat, lng], 16);
                        this.marker.setLatLng([lat, lng]);
                    }

                    this.showSuggestions = false;
                    this.emitUpdate();
                } else {
                    alert('Location not found. Try again.');
                }
            } catch (error) {
                console.error('Search error:', error);
                alert('An error occurred during the search. Please try again.');
            }
        },

        emitUpdate() {
            this.$emit('update', {
                latitude: this.latitude,
                longitude: this.longitude,
                locationName: this.locationName
            });
        },

        validate() {
            return !!this.locationName.trim();
        },
        getMapData() {
            return {
                latitude: this.latitude,
                longitude: this.longitude,
                locationName: this.locationName
            };
        },
        zoomToLocation(lat, lng, zoomLevel = 16) {
            if (this.map) {
                this.map.setView([lat, lng], zoomLevel);
            }
        },
        centerMap() {
            if (this.map) {
                this.map.setView([this.latitude, this.longitude], this.map.getZoom());
            }
        }
    },
    watch: {
        initialLatitude(newVal) {
            this.latitude = newVal;
            this.updateMarkerPosition();
        },
        initialLongitude(newVal) {
            this.longitude = newVal;
            this.updateMarkerPosition();
        },
        initialLocationName(newVal) {
            this.locationName = newVal;
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.initMap();
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.address-suggestions') && e.target.id !== 'search-address') {
                this.showSuggestions = false;
            }
        });
    },
    beforeUnmount() {
        if (this.map) {
            this.map.remove();
            this.map = null;
        }

        document.removeEventListener('click', () => {});
    }
}
</script>

<style scoped>
.event-place-map {
    width: 100%;
    height: 400px;
    display: block;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    position: relative;
    z-index: 1;
    overflow: hidden;
}

.address-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 .375rem .375rem;
    z-index: 1000;
}
.address-suggestion {
    padding: .5rem 1rem;
    cursor: pointer;
    transition: background-color .15s;
    color: white;
}
.address-suggestion:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

.coordinate-display {
    margin-top: .5rem;
    font-size: .875rem;
    color: #6c757d;
}

.vue-map-wrapper {
    position: relative;
}

.form-container {
    position: relative;
}
</style>
