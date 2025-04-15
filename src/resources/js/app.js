import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import '../css/app.css';
import 'leaflet/dist/leaflet.css';

import 'bootstrap';

import { createApp } from 'vue';
import EventMap from "./components/EventMap.vue";
import LeafletMap from './components/LeafletMap.vue';
import 'leaflet/dist/leaflet.css';

const app = createApp({});
app.component('leaflet-map', LeafletMap);
app.component('event-map', EventMap);
app.mount('#app');
