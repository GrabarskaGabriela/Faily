<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .bg-main {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }
        .custom-card-bg {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-main text-white">
<div class="page-container" id="app">
    @if(View::exists('includes.navbar'))
        @include('includes.navbar')
    @else
        <nav class="bg-dark text-white p-3">
            <div class="container">
                <h1>Faily</h1>
            </div>
        </nav>
    @endif

    <main>
        {{ $slot }}
    </main>

    @if(View::exists('includes.footer'))
        @include('includes.footer')
    @else
        <footer class="bg-dark text-white p-3 mt-5">
            <div class="container">
                <p>© 2025 Faily</p>
            </div>
        </footer>
    @endif
</div>
</body>
</html>
