<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.attendeesList') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">

@include('includes.navbar')

<main class="container mt-5 mb-5">
    <h1 class="fw-bold mb-4 text-color">{{ __('messages.title.attendeesList') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="col-md-4 text-md-end ">
        <a href="{{ route('events.show', $event) }}" class="btn text-color btn-gradient border-dark">{{ __('messages.editevent.backToEvent') }}</a>
    </div>
    <div class="table-responsive mt-3">
        <table class="table table-dark table-striped align-middle">
            <thead>
            <tr>
                <th>{{ __('messages.eventatendeestable.userLabel') }}</th>
                <th>{{ __('messages.eventatendeestable.attendeeCountLabel') }}</th>
                <th>{{ __('messages.eventatendeestable.messageLabel') }}</th>
                <th>{{ __('messages.eventatendeestable.statusLabel') }}</th>
                <th>{{ __('messages.eventatendeestable.registrationDateLabel') }}</th>
                <th>{{ __('messages.eventatendeestable.actionsLabel') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($attendees as $attendee)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $attendee->user->avatar }}" class="rounded-circle me-2" width="40" alt="{{ $attendee->user->name }}">
                            <div>
                                <div>{{ $attendee->user->name }}</div>
                                <small>{{ $attendee->user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $attendee->attendees_count }}</td>
                    <td>{{ $attendee->message }}</td>
                    <td>
                        @if($attendee->status == 'pending')
                            <span class="badge bg-warning">{{ __('messages.eventatendeestable.pendingStatusLabel') }}</span>
                        @elseif($attendee->status == 'accepted')
                            <span class="badge bg-success">{{ __('messages.eventatendeestable.acceptedStatusLabel') }}</span>
                        @elseif($attendee->status == 'rejected')
                            <span class="badge bg-danger">{{ __('messages.eventatendeestable.rejectedStatusLabel') }}</span>
                        @endif
                    </td>
                    <td>{{ $attendee->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($attendee->status == 'pending')
                            <div class="btn-group" role="group">
                                <form action="{{ route('events.attendees.update', ['event' => $event, 'attendee' => $attendee]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" class="btn btn-sm btn-success">{{ __('messages.eventatendeestable.acceptButton') }}</button>
                                </form>

                                <form action="{{ route('events.attendees.update', ['event' => $event, 'attendee' => $attendee]) }}" method="POST" class="ms-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.eventatendeestable.rejectButton') }}</button>
                                </form>
                            </div>
                        @endif

                        <form action="{{ route('events.attendees.destroy', ['event' => $event, 'attendee' => $attendee]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger ms-1">{{ __('messages.eventatendeestable.deleteButton') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">{{ __('messages.eventatendeestable.noApplicationsLabel') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</main>

@include('includes.footer')

</body>
</html>
