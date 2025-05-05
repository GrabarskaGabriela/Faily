<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.userAttendances') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">

@include('includes.navbar')

<main class="container mt-5 mb-5">
    <div class="col-md-4 text-md-end">
        <a href="{{ route('events.show', $event) }}" class="btn text-color " style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">{{ __('messages.editevent.backToEvent') }}</a>
    </div>
    <h1 class="fw-bold mb-4 text-color">{{ __('messages.userattendances.title') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        @forelse ($attendances as $attendance)
            <div class="col-md-4 mb-4">
                <div class="card text-black h-100 shadow text-color
                         {{ $attendance->status == 'rejected' ? 'border border-danger border-3' : '' }}"
                     style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    <a href="{{ route('events.show', $attendance->event->id) }}">
                        @if(isset($attendance->event->photos) && count($attendance->event->photos) > 0)
                            <img src="{{ asset('storage/' . $attendance->event->photos[0]->path) }}"
                                 alt="{{ $attendance->event->title }}"
                                 class="card-img-top"
                                 style="height: 250px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/includes/brak_zdjecia.jpg') }}"
                                 alt="{{ __('messages.userattendances.noPhoto') }}"
                                 class="card-img-top w-100"
                                 style="height: 250px; object-fit: cover;">
                        @endif
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $attendance->event->title }}</h5>

                        <div class="mb-3">
                            <span class="fw-bold">{{ __('messages.userattendances.status') }}</span>
                            @if($attendance->status == 'pending')
                                <span class="badge bg-warning text-color">{{ __('messages.userattendances.pending') }}</span>
                            @elseif($attendance->status == 'accepted')
                                <span class="badge bg-success">{{ __('messages.userattendances.accepted') }}</span>
                            @elseif($attendance->status == 'rejected')
                                <span class="badge bg-danger">{{ __('messages.userattendances.rejected') }}</span>
                                <small class="d-block text-muted">
                                    {{ __('messages.userattendances.rejectedDate') }} {{ \Carbon\Carbon::parse($attendance->updated_at)->format('d.m.Y') }}
                                </small>
                            @endif
                        </div>

                        <p><strong>{{ __('messages.userattendances.eventDate') }}</strong> {{ \Carbon\Carbon::parse($attendance->event->date)->format('d.m.Y H:i') }}</p>
                        <p><strong>{{ __('messages.userattendances.eventLocation') }}</strong> {{ $attendance->event->location_name }}</p>
                        <p><strong>{{ __('messages.userattendances.peopleCount') }}</strong> {{ $attendance->attendees_count }}</p>

                        @if($attendance->event->has_ride_sharing)
                            <p>
                                     <span class="badge bg-success">
                                         <i class="bi bi-car-front"></i>{{ __('messages.userattendances.availableRides') }}
                                     </span>
                            </p>
                        @endif

                        <p class="text-truncate">{{ Str::limit($attendance->event->description, 100) }}</p>

                        @if($attendance->message)
                            <div class="mt-2">
                                <strong>{{ __('messages.userattendances.userMessage') }}</strong>
                                <p class="small">{{ Str::limit($attendance->message, 100) }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('events.show', $attendance->event->id) }}" class="btn text-color" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">{{ __('messages.userattendances.view') }}</a>

                        @if($attendance->status != 'rejected')
                            <form action="{{ route('events.attendees.destroy', [$attendance->event->id, $attendance->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('messages.userattendances.cancelConfirmation') }}')">
                                    {{ __('messages.userattendances.cancel') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    {{ __('messages.userattendances.noUpcomingEvents') }}
                    <div class="mt-3">
                        <a href="{{ route('events.feed') }}" class="btn btn-gradient text-color">
                            {{ __('messages.userattendances.browseEvents') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</main>

@include('includes.footer')
</body>
</html>
