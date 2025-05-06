import { createI18n } from 'vue-i18n';

const messages = {
    pl: {
        messages: {
            map: {
                searchPlace: 'Wyszukaj miejsce',
                myLocation: 'Moja lokalizacja',
                locating: 'Lokalizowanie...',
                yourLocation: 'Twoja lokalizacja',
                mapInfo: 'Informacje o mapie',
                clickMapInstruction: 'Kliknij na mapę, aby dodać znacznik',
                latitude: 'Szerokość geograficzna:',
                longitude: 'Długość geograficzna:',
                noResults: 'Brak wyników',
                alertGeolocalization: 'Nie można określić Twojej lokalizacji'
            },
            title: {
                map: 'Mapa'
            }
        }
    }
};

const i18n = createI18n({
    legacy: false,
    locale: document.documentElement.lang || 'pl',
    fallbackLocale: 'pl',
    messages
});

export default i18n;
