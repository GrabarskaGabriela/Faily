# Instrukcja uruchomienia projektu

## Wymagania wstępne
- Docker i Docker Compose
- Git

## Krok 1: Klonowanie repozytorium
```bash
git clone [link-do-repozytorium]
cd [nazwa-folderu]
```

## Krok 2: Przygotowanie środowiska
1. Upewnij się, że porty 63851, 63852, 63853, 63854 i 5173 są wolne na Twoim komputerze

2. Uruchom kontenery Docker:
```bash
docker-compose up -d
```

## Krok 3: Konfiguracja projektu
Po uruchomieniu kontenerów wykonaj następujące polecenia:

```bash
# Wejdź do kontenera aplikacji
docker exec -it faily-app-dev bash

# Przejdź do katalogu aplikacji
cd /application

# Zainstaluj zależności Composer
composer install

# Zainstaluj zależności npm
npm install

# Skompiluj zasoby (dla produkcji)
npm run build

# LUB uruchom serwer dev (dla rozwoju)
npm run dev -- --host
```

## Krok 4: Konfiguracja bazy danych
```bash
# Nadal wewnątrz kontenera
php artisan migrate
```

## Krok 5: Dostęp do aplikacji
- Aplikacja Laravel: http://localhost:63851
- Panel Mailpit (do testowania e-maili): http://localhost:63854

## Rozwiązywanie typowych problemów

### Problem z uprawnieniami do katalogów
```bash
docker exec -it faily-app-dev bash
cd /application
chmod -R 777 storage bootstrap/cache
```

### Problemy z kompilacją zasobów
```bash
docker exec -it faily-app-dev bash
cd /application
npm run build
php artisan view:clear
```

### Problemy z bazą danych
```bash
docker exec -it faily-app-dev bash
cd /application
php artisan migrate:fresh
```

## Uruchamianie projektu

### Pierwsze uruchomienie
Wykonaj kroki 1-4 z powyższej instrukcji. Pierwsze uruchomienie wymaga pełnej konfiguracji środowiska.

### Ponowne uruchomienie (jeśli projekt był już wcześniej skonfigurowany)
```bash
# Uruchom kontenery
docker-compose up -d

# Opcjonalnie: Uruchom serwer deweloperski Vite
docker exec -it faily-app-dev bash
cd /application
npm run dev -- --host
```

## Rozwój projektu

### Uruchamianie serwera deweloperskiego Vite
```bash
docker exec -it faily-app-dev bash
cd /application
npm run dev -- --host
```
To polecenie uruchomi serwer, który będzie automatycznie odświeżał zmiany w plikach JavaScript i CSS.

### Zatrzymywanie środowiska
```bash
docker-compose down
```

### Całkowite usunięcie środowiska wraz z danymi
```bash
docker-compose down -v
```

## Struktura projektu
- `environment/dev/app/` - pliki konfiguracyjne Docker
- `src/` - kod źródłowy Laravel 
- `src/resources/js/components/` - komponenty Vue.js
- `src/resources/css/` - pliki CSS i SCSS

Ta instrukcja zawiera wszystkie niezbędne kroki do uruchomienia i pracy z projektem. W razie dodatkowych pytań, warto zajrzeć do oficjalnej dokumentacji Laravel i Vue.js.


jak wyświetlanie zdjęci nie działa trzeba wykonać `php artisan storage:link`