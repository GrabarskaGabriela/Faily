<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.rideMenagement') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<main class="bg-main">
@include('includes.navbar')

<div class="container mt-5 text-color ">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ __('messages.riderequestindex.rideMenagement') }}</h2>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('events.show', $ride->event) }}" class="btn btn-gradient">{{ __('messages.riderequestindex.backToEvent') }}</a>
        </div>
    </div>

    <div class="card custom-card-bg text-color mb-4 shadow-sm">
        <div class="card-header">
            <h4>{{ __('messages.riderequestindex.rideInfo') }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>{{ __('messages.riderequestindex.eventName') }}</strong> {{ $ride->event->title }}</p>
                    <p><strong>{{ __('messages.riderequestindex.eventDate') }}</strong> {{ \Carbon\Carbon::parse($ride->event->date)->format('d.m.Y H:i') }}</p>
                    <p><strong>{{ __('messages.riderequestindex.eventInfo') }}</strong> {{ $ride->vehicle_description }}</p>
                </div>
                <div class="col-md-6">
                    @php
                        $takenSeats = $ride->requests()->where('status', 'accepted')->count();
                        $availableSeats = max(0, $ride->available_seats - $takenSeats);
                    @endphp
                    <p><strong>{{ __('messages.riderequestindex.availableSeats') }}</strong> {{ $availableSeats }} / {{ $ride->available_seats }}</p>
                    <p><strong>{{ __('messages.riderequestindex.eventLocation') }}</strong> {{ $ride->meeting_location_name }}</p>
                    <p>
                        <a href="{{ route('rides.edit', $ride) }}" class="btn btn-sm btn-gradient-secondary">{{ __('messages.riderequestindex.editRide') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card custom-card-bg text-color shadow-sm">
        <div class="card-header">
            <h4>{{ __('messages.riderequestindex.applicationList') }} ({{ $requests->count() }})</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped">
                    <thead>
                    <tr>
                        <th>{{ __('messages.riderequestindex.passenger') }}</th>
                        <th>{{ __('messages.riderequestindex.message') }}</th>
                        <th>{{ __('messages.riderequestindex.status') }}</th>
                        <th>{{ __('messages.riderequestindex.applicationDate') }}</th>
                        <th>{{ __('messages.riderequestindex.actions') }}</th>
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
                            <td>{{ $request->message ?? __('messages.riderequestindex.noMessages') }}</td>
                            <td>
                                @if($request->status == 'pending')
                                    <span class="badge bg-warning">{{ __('messages.riderequestindex.awaiting') }}</span>
                                @elseif($request->status == 'accepted')
                                    <span class="badge bg-success">{{ __('messages.riderequestindex.accepted') }}</span>
                                @elseif($request->status == 'rejected')
                                    <span class="badge bg-danger">{{ __('messages.riderequestindex.declined') }}</span>
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
                                            <button type="submit" class="btn btn-sm btn-success" {{ $availableSeats < 1 ? 'disabled' : '' }}>{{ __('messages.riderequestindex.accept') }}</button>
                                        </form>

                                        <form action="{{ route('ride-requests.update', $request) }}" method="POST" class="ms-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.riderequestindex.decline') }}</button>
                                        </form>
                                    </div>
                                @elseif($request->status == 'accepted' || $request->status == 'rejected')
                                    <form action="{{ route('ride-requests.update', $request) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $request->status == 'accepted' ? 'rejected' : 'accepted' }}">
                                        <button type="submit" class="btn btn-sm {{ $request->status == 'accepted' ? 'btn-danger' : 'btn-success' }}" {{ $request->status == 'rejected' && $availableSeats < 1 ? 'disabled' : '' }}>
                                            {{ $request->status == 'accepted' ? __('messages.riderequestindex.declineAcceptance') : __('messages.riderequestindex.accept') }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('messages.riderequestindex.noApplications') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</main>
@include('includes.footer')
</body>
</html>
