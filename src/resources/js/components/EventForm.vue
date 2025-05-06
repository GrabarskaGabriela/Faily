<template>
    <div class="container">
        <div class="main-container">
            <h2 class="mb-4">Dodaj wydarzenie</h2>

            <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>
            <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

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
                            <label for="people_count" class="form-label">Ilość osób</label>
                            <input
                                type="number"
                                class="form-control"
                                id="people_count"
                                v-model="eventData.people_count"
                                required
                            >
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" :disabled="isLoading">
                    <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span v-if="!isLoading">Zapisz</span>
                    <span v-else>Zapisywanie...</span>
                </button>
            </form>
        </div>
    </div>

    <ride-sharing ref="rideSharing" />
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            eventData: {
                title: '',
                description: '',
                date: '',
                people_count: 0
            },
            isLoading: false,
            successMessage: '',
            errorMessage: ''
        };
    },
    mounted() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        }
    },
    methods: {
        async submitForm() {
            this.isLoading = true;
            this.successMessage = '';
            this.errorMessage = '';

            try {
                const formData = new FormData();

                // Dodaj dane wydarzenia
                for (const key in this.eventData) {
                    formData.append(key, this.eventData[key]);
                }

                // Dodaj dane współdzielenia przejazdu
                const rideSharingData = this.$refs.rideSharing?.getRideSharingData?.() || {};
                for (const key in rideSharingData) {
                    formData.append(key, rideSharingData[key]);
                }

                const response = await axios.post('/events', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                this.successMessage = 'Wydarzenie zostało zapisane!';
                this.eventData = {
                    title: '',
                    description: '',
                    date: '',
                    people_count: 0
                };
            } catch (error) {
                this.errorMessage = 'Wystąpił błąd przy zapisie.';
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        }

    }
};
</script>
