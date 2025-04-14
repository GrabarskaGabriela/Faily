import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import '../css/app.css';
import 'leaflet/dist/leaflet.css';


// Najpierw zaimportuj Bootstrap JS
import 'bootstrap';

// Dopiero potem Vue
import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';
import LeafletMap from './components/LeafletMap.vue';
import 'leaflet/dist/leaflet.css';

const app = createApp({});
app.component('leaflet-map', LeafletMap);
app.mount('#app');
