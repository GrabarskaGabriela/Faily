<template>
    <div class="page-container" id="app">
        <main>
            <LeafletMap :events="events" />
        </main>
    </div>
</template>

<script>
import LeafletMap from './LeafletMap.vue';

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
.map-controls, .map-info, .search-container {
    position: absolute;
    z-index: 1000;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 80%);
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}
.map-controls { top: 10px; right: 10px; }
.map-info { bottom: 30px; left: 10px; max-width: 300px; }
.search-container { top: 10px; left: 60px; width: 300px; }
.search-results {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 80%);
    border: 1px solid #000000;
    max-height: 200px;
    color: white;
    overflow-y: auto;
    display: none;
}
.search-result-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #ffffff;
}
.search-result-item:hover {
    background: linear-gradient(135deg, #1a1a2e 0%, #1e2d51 80%);
}
.leaflet-control-zoom a {
    color: white !important;
    background: linear-gradient(135deg, #1a1a2e 0%, #1e2d51 80%);
    border: none;
}

.leaflet-control-zoom a:hover {
    background-color: #2a2a4e;
}

@media (max-width: 768px) {
    .search-container { width: calc(100% - 120px); left: 10px; }
    .map-info { bottom: 10px; max-width: calc(100% - 20px); }
}

</style>
