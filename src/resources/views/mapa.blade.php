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
<div id="app" class="container-fluid flex-grow-1 p-0"> <!-- container-fluid for full width -->
    <main class="h-100"> <!-- Added h-100 class for full height -->
        <div class="row g-0 h-100"> <!-- Added h-100 class for full height -->
            <div class="col-12 h-100"> <!-- Added h-100 class for full height -->
                <div class="map-wrapper h-100"> <!-- Using h-100 instead of fixed height -->
                    <leaflet-map :center="[51.2101, 16.1619]" :zoom="7"></leaflet-map>
                </div>
            </div>
        </div>
    </main>
</div>
@include('includes.footer')
</body>
</html>
