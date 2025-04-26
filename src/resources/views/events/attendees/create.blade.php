<x-app-layout>
    <div class="container mt-5 text-white">
        <div class="card custom-card-bg">
            <div class="card-header">
                <h2>Zapisz się na wydarzenie: {{ $event->title }}</h2>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>Szczegóły wydarzenia:</h5>
                    <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y H:i') }}</p>
                    <p><strong>Miejsce:</strong> {{ $event->location_name }}</p>
                    <p><strong>Dostępne miejsca:</strong> {{ $event->getAvailableSpotsCount() }}</p>
                </div>

                <form action="{{ route('events.attendees.store', $event) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="attendees_count" class="form-label">Liczba osób</label>
                        <input type="number" class="form-control" id="attendees_count" name="attendees_count" min="1" max="{{ min(10, $event->getAvailableSpotsCount()) }}" value="1" required>
                        <div class="form-text text-light">Maksymalnie {{ min(10, $event->getAvailableSpotsCount()) }} osób</div>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Wiadomość dla organizatora (opcjonalnie)</label>
                        <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">Anuluj</a>
                        <button type="submit" class="btn btn-primary">Zapisz się</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
