<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.map') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        window.events = @json($events);
    </script>
    <script>
        window.locale = "{{ app()->getLocale() }}";
    </script>
    @include('includes.navbar')
</head>
<body>

<div class="page-container" id="app">
    <main>
        <leaflet-map :initial-events="initialEvents"></leaflet-map>
    </main>

    @include('includes.footer')
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</body>
</html>
