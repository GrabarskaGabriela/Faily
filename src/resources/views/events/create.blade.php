<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Faily - dodaj wydarzenie (Vue)</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-main text-white">
<div class="page-container" id="app">
    @include('includes.navbar')
    <main class="py-4">
        <!-- Tutaj używamy komponentu Vue -->
        <event-form></event-form>
    </main>
    @include('includes.footer')
</div>
</body>
</html>
