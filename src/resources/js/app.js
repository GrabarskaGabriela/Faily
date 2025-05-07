import 'bootstrap/dist/css/bootstrap.min.css';
import '../css/app.css';
import 'leaflet/dist/leaflet.css';

import 'bootstrap';

import { createApp } from 'vue';
import { createI18n} from "vue-i18n";

import LeafletMap from './components/LeafletMap.vue';
import EventMap from "./components/EventMap.vue";
import EventForm from "./components/EventForm.vue";
import EventPlaceMap from "./components/EventPlaceMap.vue";
import RideSharing from "./components/RideSharing.vue";
import Test from "./components/Test.vue";

import pl from './i18n/pl.json'
import en from './i18n/en.json'
import jp from './i18n/jp.json'

const app = createApp({});

const i18n = createI18n({
    legacy: false,
    locale: window.locale || 'pl',
    fallbackLocale: 'en',
    messages: {
        pl,
        en,
        jp,
    }
});


app.component('leaflet-map', LeafletMap);
app.component('event-map', EventMap);
app.component('event-map-place', EventPlaceMap);
app.component('ride-sharing', RideSharing);
app.component('event-form', EventForm);
app.component('test' ,Test);

app.use(i18n);

app.mount('#app');
