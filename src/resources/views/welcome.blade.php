<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel + Vue + Bootstrap</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="#">My App</a>
            </div>
        </nav>
        
        <div class="container mt-3">
            <h1>
                To jest statyczny nagłówek HTML
            </h1>
            <example-component></example-component>
        </div>
    </div>
</body>
</html>