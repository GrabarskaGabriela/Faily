<x-app-layout>
    <div class="container mt-5 text-white">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Edytuj wydarzenie</h2>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">Powrót do wydarzenia</a>
            </div>
        </div>

        <div class="card custom-card-bg mb-4">
            <div class="card-header">
                <h4>Formularz edycji</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="title" class="form-label">Tytuł wydarzenia</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $event->title) }}" required>
                        @error('title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Opis wydarzenia</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $event->description) }}</textarea>
                        @error('description')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Data i godzina</label>
                        <input type="datetime-local" class="form-control" id="date" name="date" value="{{ old('date', $event->date->format('Y-m-d\TH:i')) }}" required>
                        @error('date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="location_name" class="form-label">Nazwa miejsca</label>
                        <input type="text" class="form-control" id="location_name" name="location_name" value="{{ old('location_name', $event->location_name) }}" required>
                        @error('location_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Szerokość geograficzna</label>
                            <input type="number" step="any" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $event->latitude) }}" required>
                            @error('latitude')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Długość geograficzna</label>
                            <input type="number" step="any" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $event->longitude) }}" required>
                            @error('longitude')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="people_count" class="form-label">Limit uczestników</label>
                        <input type="number" class="form-control" id="people_count" name="people_count" value="{{ old('people_count', $event->people_count) }}" required min="1">
                        @error('people_count')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="has_ride_sharing" name="has_ride_sharing" value="1" {{ old('has_ride_sharing', $event->has_ride_sharing) ? 'checked' : '' }}>
                        <label class="form-check-label" for="has_ride_sharing">Umożliw współdzielenie przejazdów</label>
                        @error('has_ride_sharing')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dodaj zdjęcia w tym samym formularzu -->
                    <div class="mb-3">
                        <label for="photos" class="form-label">Dodaj nowe zdjęcia</label>
                        <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*">
                        <small class="text-muted">Możesz wybrać wiele plików naraz</small>
                        @error('photos')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('photos.*')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">Anuluj</a>
                    </div>
                </form>
            </div>
        </div>

        @if($event->photos->count() > 0)
            <div class="card custom-card-bg mb-4">
                <div class="card-header">
                    <h4>Istniejące zdjęcia</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($event->photos as $photo)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $photo->path) }}" class="card-img-top" alt="Zdjęcie wydarzenia">
                                    <div class="card-body">
                                        <form action="{{ route('photos.destroy', $photo) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć to zdjęcie?')">Usuń</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
