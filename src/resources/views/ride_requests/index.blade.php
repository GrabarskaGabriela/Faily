@extends('layouts.app')

@section('content')
    <div class="container mt-5 text-white">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Zarządzanie zgłoszeniami na przejazd</h2>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('events.show', $ride->event) }}" class="btn btn-secondary">Powrót do wydarzenia</a>
            </div>
        </div>

        <div class="card custom-card-bg text-white mb-4">
            <div class="card-header">
                <h4>Informacje o przejeździe</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Wydarzenie:</strong> {{ $ride->event->title }}</p>
                        <p><strong>Data wydarzenia:</strong> {{ \Carbon\Carbon::parse($ride->event->date)->format('d.m.Y H:i') }}</p>
                        <p><strong>Pojazd:</strong> {{ $ride->vehicle_description }}</p>
                    </div>
                    <div class="col-md-6">
                        @php
                            $takenSeats = $ride->requests()->where('status', 'accepted')->count();
                            $availableSeats = max(0, $ride->available_seats - $takenSeats);
                        @endphp
                        <p><strong>Dostępne miejsca:</strong> {{ $availableSeats }} / {{ $ride->available_seats }}</p>
                        <p><strong>Miejsce spotkania:</strong> {{ $ride->meeting_location_name }}</p>
                        <p>
                            <a href="{{ route('rides.edit', $ride) }}" class="btn btn-sm btn-primary">Edytuj przejazd</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card-bg text-white">
            <div class="card-header">
                <h4>Lista zgłoszeń ({{ $requests->count() }})</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-striped">
                        <thead>
                        <tr>
                            <th>Pasażer</th>
                            <th>Wiadomość</th>
                            <th>Status</th>
                            <th>Data zgłoszenia</th>
                            <th>Akcje</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $request->passenger->avatar }}" class="rounded-circle me-2" width="40" alt="{{ $request->passenger->name }}">
                                        <div>
                                            <div>{{ $request->passenger->name }}</div>
                                            <small>{{ $request->passenger->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $request->message ?? 'Brak wiadomości' }}</td>
                                <td>
                                    @if($request->status == 'pending')
                                        <span class="badge bg-warning">Oczekujące</span>
                                    @elseif($request->status == 'accepted')
                                        <span class="badge bg-success">Zaakceptowane</span>
                                    @elseif($request->status == 'rejected')
                                        <span class="badge bg-danger">Odrzucone</span>
                                    @endif
                                </td>
                                <td>{{ $request->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    @if($request->status == 'pending')
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('ride-requests.update', $request) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="submit" class="btn btn-sm btn-success" {{ $availableSeats < 1 ? 'disabled' : '' }}>Akceptuj</button>
                                            </form>

                                            <form action="{{ route('ride-requests.update', $request) }}" method="POST" class="ms-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger">Odrzuć</button>
                                            </form>
                                        </div>
                                    @elseif($request->status == 'accepted' || $request->status == 'rejected')
                                        <form action="{{ route('ride-requests.update', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $request->status == 'accepted' ? 'rejected' : 'accepted' }}">
                                            <button type="submit" class="btn btn-sm {{ $request->status == 'accepted' ? 'btn-danger' : 'btn-success' }}" {{ $request->status == 'rejected' && $availableSeats < 1 ? 'disabled' : '' }}>
                                                {{ $request->status == 'accepted' ? 'Anuluj akceptację' : 'Akceptuj' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Brak zgłoszeń</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
