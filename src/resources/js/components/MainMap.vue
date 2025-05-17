<template>
    <div class="page-container" id="app">
        <main>
            <LeafletMap :events="events" />
        </main>
    </div>
</template>

<script>
import LeafletMap from './LeafletMap.vue';
import '@/css/app.css';
export default {
    name: 'MainMap',

    components: {
        LeafletMap
    },

    data() {
        return {
            events: []
        };
    },

    created() {
        if (window.events && Array.isArray(window.events)) {
            this.events = window.events;
        }
    }
};
</script>

<style>
.html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}
.page-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
main {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 0 !important;
}
.map-container {
    flex: 1;
    width: 100%;
    height: 100%;
    position: relative;
    z-index: 1;
}
#map {
    width: 100%;
    height: 100%;
    position: absolute;
}

@media (max-width: 768px) {
    .search-container { width: calc(100% - 120px); left: 10px; }
    .map-info { bottom: 10px; max-width: calc(100% - 20px); }
}

</style>
