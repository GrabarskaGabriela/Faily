import 'bootstrap/dist/css/bootstrap.min.css';
import '../css/app.css';



import 'bootstrap';

import { createApp } from 'vue';
import LeafletMap from './components/LeafletMap.vue';
import EventMap from "./components/EventMap.vue";
import 'leaflet/dist/leaflet.css';
import EventForm from "./components/EventForm.vue";
import EventPlaceMap from "./components/EventPlaceMap.vue";
import RideSharing from "./components/RideSharing.vue";


const app = createApp({});
app.component('leaflet-map', LeafletMap);
app.component('event-map', EventMap);
app.component('event-map-place', EventPlaceMap);
app.component('ride-sharing', RideSharing);
app.component('event-form', EventForm);

app.mount('#app');
