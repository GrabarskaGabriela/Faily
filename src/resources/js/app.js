import 'bootstrap/dist/css/bootstrap.min.css';
import '../css/app.css';



import 'bootstrap';

import { createApp } from 'vue';
import LeafletMap from './components/LeafletMap.vue';
import MainMap from './components/MainMap.vue';
import EventMap from "./components/EventMap.vue";
import 'leaflet/dist/leaflet.css';
//import i18n from './i18n';
import EventForm from "./components/EventForm.vue";
import EventPlaceMap from "./components/EventPlaceMap.vue";
import RideSharing from "./components/RideSharing.vue";

const initialEvents = window.events || [];
const app = createApp({
    data() {
        return {
            initialEvents: window.events || []
        };
    }
});
app.component('leaflet-map', LeafletMap);
app.component('main-map', MainMap);
//app.use(i18n);
app.component('event-map', EventMap);
app.component('event-map-place', EventPlaceMap);
app.component('ride-sharing', RideSharing);
app.component('event-form', EventForm);

app.mount('#app');
