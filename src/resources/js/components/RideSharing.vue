<template>
    <div class="ride-sharing">
        <div class="mb-3 form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                id="has-ride-sharing"
                v-model="hasRideSharing"
            >
            <label class="form-check-label" for="has-ride-sharing">Włącz współdzielenie przejazdów</label>
        </div>

        <div v-if="hasRideSharing" class="ride-sharing-form mt-4 mb-4 p-3">
            <h4 class="mb-3">Szczegóły przejazdu</h4>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="vehicle-description" class="form-label">Opis pojazdu</label>
                        <input
                            type="text"
                            class="form-control"
                            id="vehicle-description"
                            v-model="vehicleDescription"
                            placeholder="np. Czerwony Ford Focus"
                            required
                        >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="available-seats" class="form-label">Dostępna liczba miejsc</label>
                        <input
                            type="number"
                            class="form-control"
                            id="available-seats"
                            v-model="availableSeats"
                            min="1"
                            required
                        >
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="meeting-location-name" class="form-label">Nazwa miejsca spotkania</label>
                        <input
                            type="text"
                            class="form-control"
                            id="meeting-location-name"
                            v-model="meetingLocationData.locationName"
                            placeholder="np. Parking przy galerii"
                            required
                        >
                    </div>
                </div>

                <div class="col-md-12">
                    <event-place-map
                        ref="meetingMap"
                        :initial-latitude="meetingLocationData.latitude"
                        :initial-longitude="meetingLocationData.longitude"
                        :initial-location-name="meetingLocationData.locationName"
                        map-container-id="vue-meeting-map"
                        @update="updateMeetingLocation"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EventPlaceMap from './EventPlaceMap.vue';

export default {
    name: 'RideSharing',
    components: {
        'event-place-map': EventPlaceMap
    },
    props: {
        initialHasRideSharing: {
            type: Boolean,
            default: false
        },
        initialVehicleDescription: {
            type: String,
            default: ''
        },
        initialAvailableSeats: {
            type: Number,
            default: 1
        },
        initialMeetingLocationData: {
            type: Object,
            default: () => ({
                latitude: 52.069,
                longitude: 19.480,
                locationName: ''
            })
        }
    },
    data() {
        return {
            hasRideSharing: this.initialHasRideSharing,
            vehicleDescription: this.initialVehicleDescription,
            availableSeats: this.initialAvailableSeats,
            meetingLocationData: { ...this.initialMeetingLocationData }
        };
    },
    watch: {
        hasRideSharing(newVal) {
            this.$emit('toggle-ride-sharing', newVal);

            if (newVal) {
                this.$nextTick(() => {
                    if (this.$refs.meetingMap) {
                        this.$refs.meetingMap.centerMap();
                    }
                });
            }
        }
    },
    methods: {
        updateMeetingLocation(data) {
            this.meetingLocationData = { ...data };
            this.$emit('update-meeting-location', this.meetingLocationData);
        },

        validate() {
            if (!this.hasRideSharing) {
                return true;
            }

            if (!this.vehicleDescription.trim()) {
                alert('Podaj opis pojazdu');
                return false;
            }

            if (!this.availableSeats || this.availableSeats < 1) {
                alert('Podaj prawidłową liczbę dostępnych miejsc');
                return false;
            }

            if (!this.meetingLocationData.locationName.trim()) {
                alert('Wybierz miejsce spotkania');
                return false;
            }

            if (this.$refs.meetingMap) {
                return this.$refs.meetingMap.validate();
            }

            return true;
        },


        getRideSharingData() {
            if (!this.hasRideSharing) {
                return {
                    has_ride_sharing: false
                };
            }

            return {
                has_ride_sharing: true,
                vehicle_description: this.vehicleDescription,
                available_seats: this.availableSeats,
                meeting_location_name: this.meetingLocationData.locationName,
                meeting_latitude: this.meetingLocationData.latitude,
                meeting_longitude: this.meetingLocationData.longitude
            };
        }
    }
}
</script>

<style scoped>
.ride-sharing-form {
    background-color: rgba(255,255,255,.05);
    border-radius: .375rem;
}
</style>
