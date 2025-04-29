<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - Zapisz się na wydarzenie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">

@include('includes.navbar')

<main class="container mt-5 mb-5 text-white">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-white border-dark mb-4" style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%);">
                <div class="card-header">
                    <h4>Zapisz się na wydarzenie: {{ $event->title }}</h4>
                </div>
                <div class="card-body" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    <form action="{{ route('events.attendees.store', $event) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="attendees_count" class="form-label">Liczba uczestników</label>
                            <input type="number" class="form-control" id="attendees_count" name="attendees_count"
                                   min="1" max="10" value="1" required>
                            <div class="form-text text-light">Maksymalnie 10 osób</div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Wiadomość dla organizatora (opcjonalna)</label>
                            <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn text-white"
                                    style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">
                                Zapisz się
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">
                    Wróć do wydarzenia
                </a>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
