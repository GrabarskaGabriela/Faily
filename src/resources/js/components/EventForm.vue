<template>
    <div class="container">
        <div class="main-container">
            <h2 class="mb-4">Dodaj wydarzenie</h2>

            <form @submit.prevent="submitForm" enctype="multipart/form-data" id="event-form">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tytuł wydarzenia</label>
                            <input
                                type="text"
                                class="form-control"
                                id="title"
                                v-model="eventData.title"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Opis wydarzenia</label>
                            <textarea
                                class="form-control"
                                id="description"
                                v-model="eventData.description"
                                rows="4"
                                required
                            ></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Data wydarzenia</label>
                            <input
                                type="datetime-local"
                                class="form-control"
                                id="date"
                                v-model="eventData.date"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="people-count" class="form-label">Ilość osób</label>
                            <input
                                type="number"
                                class="form-control"
                                id="people-count"
                                v-model="eventData.people_count"
                                min="1"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="event-photos" class="form-label">Dodaj zdjęcia</label>
                            <input
                                type="file"
                                class="form-control"
                                id="event-photos"
                                ref="photos"
                                @change="handleFileUpdate"
                                multiple
                            >
                            <div id="file-list" class="mt-2 small text-muted">
                                <div v-if="selectedFiles.length > 0">
                                    <div>Wybrano plików: {{ selectedFiles.length }}</div>
                                    <ul class="mt-1 ps-3">
                                        <li v-for="(file, index) in selectedFiles" :key="index">
                                            {{ file.name }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <event-place-map
                            ref="mainMap"
                            :initial-latitude="locationData.latitude"
                            :initial-longitude="locationData.longitude"
                            :initial-location-name="locationData.locationName"
                            @update="updateEventLocation"
                        />

                        <ride-sharing
                            ref="rideSharing"
                            :initial-has-ride-sharing="rideSharingEnabled"
                            :initial-vehicle-description="vehicleDescription"
                            :initial-available-seats="availableSeats"
                            :initial-meeting-location-data="meetingLocationData"
                            @toggle-ride-sharing="toggleRideSharing"
                            @update-meeting-location="updateMeetingLocation"
                        />
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button
                        type="submit"
                        class="btn btn-lg px-4 text-white"
                        style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);"
                        :disabled="isSubmitting"
                    >
                        <i class="bi bi-check-circle me-2"></i>Dodaj wydarzenie
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import EventPlaceMap from './EventPlaceMap.vue';
import RideSharing from './RideSharing.vue';

export default {
    name: 'EventForm',
    components: {
        'event-place-map': EventPlaceMap,
        'ride-sharing': RideSharing
    },
    data() {
        return {
            eventData: {
                title: '',
                description: '',
                date: '',
                people_count: 1
            },
            locationData: {
                latitude: 52.069,
                longitude: 19.480,
                locationName: ''
            },
            rideSharingEnabled: false,
            vehicleDescription: '',
            availableSeats: 1,
            meetingLocationData: {
                latitude: 52.069,
                longitude: 19.480,
                locationName: ''
            },
            selectedFiles: [],
            isSubmitting: false
        };
    },
    methods: {
        handleFileUpdate(event) {
            this.selectedFiles = Array.from(event.target.files || []);
        },

        updateEventLocation(data) {
            this.locationData = { ...data };
        },

        toggleRideSharing(enabled) {
            this.rideSharingEnabled = enabled;
        },

        updateMeetingLocation(data) {
            this.meetingLocationData = { ...data };
        },

        validateForm() {
            if (!this.eventData.title.trim()) {
                alert('Give the title of the event');
                return false;
            }

            if (!this.eventData.description.trim()) {
                alert('Give the description of the event');
                return false;
            }

            if (!this.eventData.date) {
                alert('Select the date of the event');
                return false;
            }

            if (!this.eventData.people_count || this.eventData.people_count < 1) {
                alert('Enter the correct number of people');
                return false;
            }

            if (this.$refs.mainMap && !this.$refs.mainMap.validate()) {
                return false;
            }

            if (this.rideSharingEnabled && this.$refs.rideSharing && !this.$refs.rideSharing.validate()) {
                return false;
            }

            return true;
        },

        async submitForm() {
            if (!this.validateForm()) {
                return;
            }

            const hasFiles = this.$refs.photos && this.$refs.photos.files && this.$refs.photos.files.length > 0;

            if (hasFiles) {
                await this.submitFormWithFiles();
            } else {
                this.submitFormWithoutFiles();
            }
        },

        async submitFormWithFiles() {
            try {
                const formData = new FormData();

                Object.entries(this.eventData).forEach(([key, value]) => {
                    formData.append(key, value);
                });

                const locationName = this.locationData.locationName ||
                    `Lokalizacja (${this.locationData.latitude.toFixed(6)}, ${this.locationData.longitude.toFixed(6)})`;
                formData.append('location_name', locationName);
                formData.append('latitude', this.locationData.latitude);
                formData.append('longitude', this.locationData.longitude);

                formData.append('has_ride_sharing', this.rideSharingEnabled ? 1 : 0);

                if (this.rideSharingEnabled && this.$refs.rideSharing) {
                    const rideSharingData = this.$refs.rideSharing.getRideSharingData();
                    Object.entries(rideSharingData).forEach(([key, value]) => {
                        if (key !== 'has_ride_sharing') {
                            formData.append(key, value);
                        }
                    });
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    formData.append('_token', csrfToken);
                }

                if (this.$refs.photos && this.$refs.photos.files.length > 0) {
                    const files = Array.from(this.$refs.photos.files);
                    files.forEach(file => {
                        formData.append('photos[]', file);
                    });
                }

                console.log('Sending a form with files. FormData content:');
                for (let pair of formData.entries()) {
                    if (pair[1] instanceof File) {
                        console.log(`${pair[0]}: File (${pair[1].name}, ${pair[1].size} bytes)`);
                    } else {
                        console.log(`${pair[0]}: ${pair[1]}`);
                    }
                }

                const response = await fetch('/events', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (response.ok || (response.status >= 300 && response.status < 400)) {
                    console.log('The form has been successfully submitted!');
e
                    const redirectUrl = response.headers.get('Location');
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                    } else {
                        window.location.href = '/events';
                    }
                } else {
                    const responseText = await response.text();
                    try {
                        const errorData = JSON.parse(responseText);
                        if (errorData.errors) {
                            const messages = Object.values(errorData.errors).flat().join('\n');
                            alert(`Validation errors:\n${messages}`);
                        } else if (errorData.message) {
                            alert(errorData.message);
                        } else {
                            throw new Error('Unknown error format');
                        }
                    } catch (parseError) {
                        console.error('Response parsing error:', parseError);
                        if (responseText) {
                            console.error('Server response:', responseText);
                        }
                        alert('An error occurred while submitting the form. Check the console for more information.');
                    }
                }
            } catch (error) {
                console.error('Error while submitting form:', error);
                alert('An unexpected error occurred while submitting the form.');
            }
        },

        submitFormWithoutFiles() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/events';
            form.enctype = 'multipart/form-data';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            Object.entries(this.eventData).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });

            const locationName = this.locationData.locationName ||
                `Lokalizacja (${this.locationData.latitude.toFixed(6)}, ${this.locationData.longitude.toFixed(6)})`;

            const locationNameInput = document.createElement('input');
            locationNameInput.type = 'hidden';
            locationNameInput.name = 'location_name';
            locationNameInput.value = locationName;
            form.appendChild(locationNameInput);

            const latInput = document.createElement('input');
            latInput.type = 'hidden';
            latInput.name = 'latitude';
            latInput.value = this.locationData.latitude;
            form.appendChild(latInput);

            const lngInput = document.createElement('input');
            lngInput.type = 'hidden';
            lngInput.name = 'longitude';
            lngInput.value = this.locationData.longitude;
            form.appendChild(lngInput);

            const rideSharingInput = document.createElement('input');
            rideSharingInput.type = 'hidden';
            rideSharingInput.name = 'has_ride_sharing';
            rideSharingInput.value = this.rideSharingEnabled ? 1 : 0;
            form.appendChild(rideSharingInput);

            if (this.rideSharingEnabled && this.$refs.rideSharing) {
                const rideSharingData = this.$refs.rideSharing.getRideSharingData();
                Object.entries(rideSharingData).forEach(([key, value]) => {
                    if (key !== 'has_ride_sharing') {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        form.appendChild(input);
                    }
                });
            }

            form.style.display = 'none';
            document.body.appendChild(form);
            form.submit();
        }
    }
}
</script>

<style scoped>
.main-container {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: .5rem;
    padding: 2rem;
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
}
</style>
