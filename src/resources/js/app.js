import 'bootstrap/dist/css/bootstrap.min.css';
import '../css/app.css';

import 'bootstrap';

import { createApp } from 'vue';
import LeafletMap from './components/LeafletMap.vue';
import EventMap from "./components/EventMap.vue";
import 'leaflet/dist/leaflet.css';


const app = createApp({});
app.component('example-component', ExampleComponent);
app.component('leaflet-map', LeafletMap);
app.component('event-map', EventMap);
app.mount('#app');
