// resources/js/components/AddEventForm.vue
<template>
    <div>
        <form @submit.prevent="submitForm" enctype="multipart/form-data" id="event-form">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ $t('messages.addevent.eventTitle') }}</label>
                        <input type="text" class="form-control" id="title" name="title" v-model="eventData.title" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ $t('messages.addevent.eventDesc') }}</label>
                        <textarea class="form-control" id="description" name="description" rows="4" v-model="eventData.description" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">{{ $t('messages.addevent.date') }}</label>
                        <input type="datetime-local" class="form-control" id="date" name="date" v-model="eventData.date" required>
                    </div>

                    <div class="mb-3">
                        <label for="peopleCount" class="form-label">{{ $t('messages.addevent.availablePersonNumber') }}</label>
                        <input type="number" class="form-control" id="peopleCount" name="people_count" min="1" v-model="eventData.people_count" required>
                    </div>

                    <div class="mb-3">
                        <input type="file" class="d-none" id="eventPhotos" ref="eventPhotos" name="photos[]" multiple @change="updateFileList">
                        <label for="eventPhotos" class="btn btn-gradient text-color mt-2">
                            {{ $t('messages.addevent.addPhotos') }}
                        </label>
                        <div id="fileList" class="mt-2 small text-color">
                            <div v-if="selectedFiles.length > 0">
                                {{ $t('messages.addevent.fileSelection') }} {{ selectedFiles.length }}
                                <ul class="mt-1 ps-3">
                                    <li v-for="(file, index) in selectedFiles" :key="index">{{ file.name }}</li>
                                </ul>
                            </div>
                            <div v-else>
                                {{ $t('messages.addevent.fileNotChoosen') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <main-map
                        :initial-latitude="parseFloat(eventData.latitude)"
                        :initial-longitude="parseFloat(eventData.longitude)"
                        @update:latitude="eventData.latitude = $event"
                        @update:longitude="eventData.longitude = $event"
                        @update:location-name="eventData.location_name = $event"
                    ></main-map>

                    <div class="mb-3 form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="has_ride_sharing"
                            name="has_ride_sharing"
                            v-model="eventData.has_ride_sharing"
                            value="1"
                        >
                        <label class="form-check-label" for="has_ride_sharing">{{ $t('messages.addevent.enableCarSharing') }}</label>
                    </div>
                </div>
            </div>

            <div v-if="eventData.has_ride_sharing" class="mt-4 mb-4 p-3" id="ride-sharing-form">
                <h4 class="mb-3">{{ $t('messages.addevent.rideDetails') }}</h4>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_description" class="form-label">{{ $t('messages.addevent.carDesc') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                id="vehicle_description"
                                name="vehicle_description"
                                v-model="eventData.vehicle_description"
                                :placeholder="$t('messages.addevent.carDescExample')"
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="avalible_seats" class="form-label">{{ $t('messages.addevent.availableSeats') }}</label>
                            <input
                                type="number"
                                class="form-control"
                                id="avalible_seats"
                                name="avalible_seats"
                                min="1"
                                v-model="eventData.avalible_seats"
                            >
                        </div>
                    </div>

                    <div class="col-12">
                        <event-map
                            :initial-latitude="parseFloat(eventData.meeting_latitude)"
                            :initial-longitude="parseFloat(eventData.meeting_longitude)"
                            @update:latitude="eventData.meeting_latitude = $event"
                            @update:longitude="eventData.meeting_longitude = $event"
                            @update:location-name="eventData.meeting_location_name = $event"
                        ></event-map>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-lg px-4 btn-gradient text-color" id="submit-button">
                    <i class="bi bi-check-circle me-2"></i>{{ $t('messages.addevent.addEvent') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import EventMap from './EventMap.vue';
import EventPlaceMap from './EventPlaceMap.vue';


export default {
    name: 'AddEventForm',
    components: {
        EventMap,
        EventPlaceMap
    },

    data() {
        return {
            eventData: {
                title: '',
                description: '',
                date: '',
                people_count: 1,
                latitude: '52.069',
                longitude: '19.480',
                location_name: '',
                has_ride_sharing: false,
                vehicle_description: '',
                avalible_seats: 1,
                meeting_latitude: '52.069',
                meeting_longitude: '19.480',
                meeting_location_name: ''
            },
            selectedFiles: []
        };
    },

    methods: {
        updateFileList() {
            this.selectedFiles = Array.from(this.$refs.eventPhotos.files);
        },

        async submitForm() {
            const formData = new FormData();

            // Add all form fields to FormData
            Object.keys(this.eventData).forEach(key => {
                formData.append(key, this.eventData[key]);
            });

            // Add files
            if (this.selectedFiles.length > 0) {
                this.selectedFiles.forEach(file => {
                    formData.append('photos[]', file);
                });
            }

            try {
                // Replace with your API endpoint
                const response = await fetch(route('events.store'), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    // Handle successful form submission
                    window.location.href = route('events.index');
                } else {
                    // Handle errors
                    const errorData = await response.json();
                    console.error('Form submission failed:', errorData);
                    // Display error messages to user
                }
            } catch (error) {
                console.error('Error submitting form:', error);
            }
        }
    }
};
</script>

<style scoped>
#ride-sharing-form {
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    background-color: rgba(0, 0, 0, 0.02);
}
</style>
