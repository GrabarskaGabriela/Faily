<template>
    <div class="meeting-map-component">
        <div class="form-group">
            <label for="search-meeting-address">Search Meeting Point:</label>
            <div class="input-group search-container">
                <input
                    type="text"
                    class="form-control"
                    id="search-meeting-address"
                    v-model="searchQuery"
                    @input="handleSearchInput"
                    @keyup.enter="searchLocation"
                    placeholder="Enter meeting point address"
                />
                <button
                    class="btn btn-primary"
                    id="search-meeting-button"
                    @click="searchLocation"
                >Search</button>
            </div>

            <div v-if="suggestions.length > 0" class="address-suggestions">
                <div
                    v-for="(item, index) in suggestions"
                    :key="index"
                    class="address-suggestion"
                    @click="selectSuggestion(item)"
                >
                    {{ item.display_name }}
                </div>
            </div>
        </div>

        <div id="meeting-map-container" ref="mapContainer" style="height: 200px;"></div>

        <div class="mt-3">
            <p>Meeting point coordinates: <span id="meeting-coordinates-text">{{ coordinates }}</span></p>

            <input type="hidden" id="meeting_latitude" :value="latitude">
            <input type="hidden" id="meeting_longitude" :value="longitude">
            <input type="hidden" id="meeting_location_name" :value="locationName">
        </div>
    </div>
</template>

<script>
export default {
    name: 'MeetingMap',
    props: {
        initialLatitude: {
            type: [Number, String],
            default: 52.069
        },
        initialLongitude: {
            type: [Number, String],
            default: 19.480
        }
    },
    data() {
        return {
            map: null,
            marker: null,
            latitude: parseFloat(this.initialLatitude),
            longitude: parseFloat(this.initialLongitude),
            locationName: '',
            searchQuery: '',
            suggestions: [],
            coordinates: ''
        };
    },
    computed: {
        formattedCoordinates() {
            return `${this.latitude.toFixed(6)}, ${this.longitude.toFixed(6)}`;
        }
    },
    watch: {
        latitude() {
            this.updateFormValues();
        },
        longitude() {
            this.updateFormValues();
        }
    },
    mounted() {
        this.initMap();
        this.coordinates = this.formattedCoordinates;
        document.addEventListener('click', this.handleOutsideClick);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.handleOutsideClick);
        if (this.map) {
            this.map.remove();
            this.map = null;
        }
    },
    methods: {
        async initMap() {
            try {
                console.log('Initializing meeting point map...');

                if (!this.$refs.mapContainer) {
                    console.error('Meeting map container not found');
                    return;
                }

                this.map = L.map(this.$refs.mapContainer).setView(
                    [this.latitude, this.longitude],
                    6
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

                await this.reverseGeocode(this.latitude, this.longitude);

                setTimeout(() => {
                    this.map.invalidateSize();
                    console.log('Meeting map redrawn');
                }, 500);
            } catch (error) {
                console.error('Error initializing meeting map:', error);
            }
        },

        updateFormValues() {
            this.coordinates = this.formattedCoordinates;

            const latInput = document.getElementById('meeting_latitude');
            const lngInput = document.getElementById('meeting_longitude');
            const locNameInput = document.getElementById('meeting_location_name');

            if (latInput) latInput.value = this.latitude;
            if (lngInput) lngInput.value = this.longitude;
            if (locNameInput) locNameInput.value = this.locationName;
        },

        handleMarkerDrag(event) {
            const position = this.marker.getLatLng();
            this.latitude = position.lat;
            this.longitude = position.lng;

            this.reverseGeocode(position.lat, position.lng);
        },

        handleMapClick(e) {
            this.marker.setLatLng(e.latlng);
            this.latitude = e.latlng.lat;
            this.longitude = e.latlng.lng;

            this.reverseGeocode(e.latlng.lat, e.latlng.lng);
        },

        async reverseGeocode(lat, lng) {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
                );
                const data = await response.json();

                if (data && data.display_name) {
                    this.locationName = data.display_name.split(',').slice(0, 3).join(', ');
                    this.updateFormValues();
                }
            } catch (error) {
                console.error('Error during meeting point reverse geocoding:', error);
            }
        },

        async handleSearchInput() {
            if (this.searchQuery.trim().length < 3) {
                this.suggestions = [];
                return;
            }

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery.trim())}&limit=5`
                );
                const data = await response.json();

                if (data && data.length > 0) {
                    this.suggestions = data;
                } else {
                    this.suggestions = [];
                }
            } catch (error) {
                console.error('Error fetching meeting location suggestions:', error);
                this.suggestions = [];
            }
        },

        selectSuggestion(item) {
            const lat = parseFloat(item.lat);
            const lng = parseFloat(item.lon);

            this.searchQuery = item.display_name;
            this.latitude = lat;
            this.longitude = lng;
            this.locationName = item.display_name.split(',').slice(0, 3).join(', ');

            this.map.setView([lat, lng], 16);
            this.marker.setLatLng([lat, lng]);

            this.suggestions = [];
        },

        async searchLocation() {
            if (!this.searchQuery.trim()) return;

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery.trim())}`
                );
                const data = await response.json();

                if (data && data.length > 0) {
                    const location = data[0];
                    const lat = parseFloat(location.lat);
                    const lng = parseFloat(location.lon);

                    this.latitude = lat;
                    this.longitude = lng;
                    this.locationName = location.display_name.split(',').slice(0, 3).join(', ');

                    this.map.setView([lat, lng], 16);
                    this.marker.setLatLng([lat, lng]);

                    this.suggestions = [];
                } else {
                    alert('Meeting location not found. Please try a different search.');
                }
            } catch (error) {
                console.error('Error searching for meeting location:', error);
                alert('Error searching for meeting location. Please try again.');
            }
        },

        handleOutsideClick(event) {
            if (this.suggestions.length > 0 &&
                !event.target.closest('.search-container') &&
                !event.target.closest('.address-suggestions')) {
                this.suggestions = [];
            }
        }
    }
};
</script>

<style scoped>
.search-container {
    position: relative;
    margin-bottom: 10px;
}

.address-suggestions {
    position: absolute;
    z-index: 1000;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.address-suggestion {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.address-suggestion:hover {
    background-color: #f5f5f5;
}
</style>
