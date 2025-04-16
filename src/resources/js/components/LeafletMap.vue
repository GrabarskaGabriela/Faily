<template>
    <div class="map-container">
        <div :id="mapId" class="map-element"></div>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css'; // Dodaj bezpośredni import CSS

// Naprawianie problemu z ikonami
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow
});

export default {
    name: 'LeafletMap', // Dodaj nazwę komponentu
    props: {
        center: {
            type: Array,
            default: () => [51.2101, 16.1619] // Legnica
        },
        zoom: {
            type: Number,
            default: 7
        },
        markers: {
            type: Array,
            default: () => []
        }
    },
    setup(props) {
        const mapId = ref('map-' + Math.random().toString(36).substring(2, 9));
        let map = null;
        let resizeObserver = null;

        const initMap = () => {
            console.log('Inicjalizacja mapy w elemencie:', mapId.value);
            const mapElement = document.getElementById(mapId.value);

            if (!mapElement) {
                console.error('Element mapy nie istnieje:', mapId.value);
                return;
            }

            // Sprawdź rozmiar kontenera
            console.log('Rozmiar kontenera:', mapElement.offsetWidth, 'x', mapElement.offsetHeight);

            if (mapElement.offsetWidth === 0 || mapElement.offsetHeight === 0) {
                console.warn('Kontener mapy ma zerowy rozmiar!');
                // Ustaw minimalny rozmiar dla pewności
                mapElement.style.minHeight = '400px';
                mapElement.style.minWidth = '100%';
            }

            try {
                // Inicjalizacja mapy
                map = L.map(mapId.value, {
                    zoomControl: true,
                    scrollWheelZoom: true
                }).setView(props.center, props.zoom);

                // Dodanie warstwy OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);

                // Dodanie domyślnego markera na środku mapy
                L.marker(props.center).addTo(map)
                    .bindPopup('Tu jesteśmy :)')
                    .openPopup();

                // Dodanie dodatkowych markerów
                if (props.markers.length > 0) {
                    props.markers.forEach(markerData => {
                        const marker = L.marker([markerData.lat, markerData.lng]).addTo(map);

                        if (markerData.popup) {
                            marker.bindPopup(markerData.popup);
                        }
                    });
                }

                console.log('Mapa zainicjalizowana pomyślnie');
            } catch (error) {
                console.error('Błąd podczas inicjalizacji mapy:', error);
            }

            // Nasłuchiwanie na zdarzenie resize okna
            window.addEventListener('resize', handleResize);

            // Użyj ResizeObserver dla dokładniejszego śledzenia zmian rozmiaru
            if (typeof ResizeObserver !== 'undefined') {
                resizeObserver = new ResizeObserver(() => {
                    if (map) {
                        console.log('ResizeObserver wykrył zmianę rozmiaru');
                        map.invalidateSize();
                    }
                });

                if (mapElement) {
                    resizeObserver.observe(mapElement);
                }
            }

            // Przeładuj mapę po renderowaniu komponentu
            setTimeout(() => {
                if (map) {
                    console.log('Odświeżanie rozmiaru mapy po 100ms');
                    map.invalidateSize();
                }
            }, 100);
        };

        const handleResize = () => {
            if (map) {
                console.log('Wykryto zmianę rozmiaru okna');
                map.invalidateSize();
            }
        };

        onMounted(() => {
            console.log('Komponent LeafletMap zamontowany');
            // Małe opóźnienie może pomóc upewnić się, że DOM jest gotowy
            setTimeout(() => {
                initMap();
            }, 100);

            // Dodatkowe opóźnione odświeżenie po pełnym renderowaniu strony
            setTimeout(() => {
                if (map) map.invalidateSize();
            }, 500);

            // Dodatkowe odświeżenie po dłuższym czasie
            setTimeout(() => {
                if (map) map.invalidateSize();
            }, 1500);
        });

        onUnmounted(() => {
            console.log('Odmontowywanie komponentu mapy');
            // Usuń nasłuchiwanie na zdarzenie resize
            window.removeEventListener('resize', handleResize);

            // Zatrzymaj obserwatora zmian rozmiaru
            if (resizeObserver) {
                resizeObserver.disconnect();
            }

            // Zniszcz mapę
            if (map) {
                map.remove();
                map = null;
            }
        });

        return {
            mapId,
            getMap: () => map
        };
    }
}
</script>

<style>
.map-container {
    width: 100%;
    height: 600px; /* Sztywna wysokość */
    position: relative;
}

.map-element {
    width: 100%;
    height: 100%;
    min-height: 400px; /* Minimalna wysokość */
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

/* Style dla kontenera Leaflet */
:deep(.leaflet-container) {
    width: 100%;
    height: 100%;
    z-index: 1;
}
</style>
