<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')
<div id="app" class="flex-grow-1">
    <main class="d-flex flex-column">
        <div class="map-container flex-grow-1">
            <leaflet-map :center="[51.2101, 16.1619]" :zoom="7"></leaflet-map>
        </div>
    </main>
</div>
@include('includes.footer')
</body>
</html>
